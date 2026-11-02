<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\Memory;
use App\Models\MemoryMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MemoryGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected Couple $couple;
    protected User $partner1;
    protected User $partner2;
    protected User $child;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('local');

        $this->couple = Couple::factory()->create();

        $this->partner1 = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Misbachul Munir',
            'role' => 'partner',
            'must_change_password' => false,
        ]);

        $this->partner2 = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Syahra Alfi Nur Arintia',
            'role' => 'partner',
            'must_change_password' => false,
        ]);

        $this->child = User::factory()->create([
            'couple_id' => $this->couple->id,
            'name' => 'Anak Tersayang',
            'role' => 'child',
            'must_change_password' => false,
        ]);
    }

    public function test_partner_can_store_memory_with_media_uploads(): void
    {
        $file = UploadedFile::fake()->create('first_date.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($this->partner1)->post(route('memories.store'), [
            'title' => 'Kencan Pertama di Cafe Nostalgia',
            'memory_date' => '2024-05-12',
            'location' => 'Bandung',
            'description' => 'Hari paling manis saat pertama kali kita minum kopi berdua.',
            'is_milestone' => '1',
            'media' => [$file],
        ]);

        $response->assertRedirect(route('memories.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('memories', [
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Kencan Pertama di Cafe Nostalgia',
            'is_milestone' => true,
        ]);

        $memory = Memory::first();
        $this->assertCount(1, $memory->media);

        $media = $memory->media->first();
        Storage::disk('local')->assertExists($media->file_path);
    }

    public function test_child_can_view_family_gallery(): void
    {
        Memory::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Liburan Keluarga di Pantai',
            'memory_date' => '2025-07-10',
            'is_milestone' => false,
        ]);

        $response = $this->actingAs($this->child)->get(route('memories.index'));

        $response->assertOk();
        $response->assertSee('Liburan Keluarga di Pantai');
    }

    public function test_private_media_delivery_with_signed_url_and_auth(): void
    {
        $memory = Memory::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Momen Indah',
            'memory_date' => '2025-01-01',
        ]);

        $filePath = "memories/{$this->couple->id}/{$memory->id}/photo.jpg";
        Storage::disk('local')->put($filePath, 'fake-image-binary-content');

        $media = MemoryMedia::create([
            'memory_id' => $memory->id,
            'file_path' => $filePath,
            'media_type' => 'image',
        ]);

        // 1. Partner in couple can access
        $responsePartner = $this->actingAs($this->partner1)->get(route('media.memory', $media));
        $responsePartner->assertOk();

        // 2. Stranger from another couple is blocked (403)
        $stranger = User::factory()->create(['couple_id' => Couple::factory()->create()->id]);
        $responseStranger = $this->actingAs($stranger)->get(route('media.memory', $media));
        $responseStranger->assertStatus(403);

        // 3. Temporary Signed URL allows access
        $signedUrl = $media->signed_url;
        $responseSigned = $this->get($signedUrl);
        $responseSigned->assertOk();
    }

    public function test_deleting_memory_removes_physical_files(): void
    {
        $memory = Memory::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Kenangan Sementara',
            'memory_date' => '2025-01-01',
        ]);

        $filePath = "memories/{$this->couple->id}/{$memory->id}/photo.jpg";
        Storage::disk('local')->put($filePath, 'image-content');

        $media = MemoryMedia::create([
            'memory_id' => $memory->id,
            'file_path' => $filePath,
            'media_type' => 'image',
        ]);

        Storage::disk('local')->assertExists($filePath);

        $response = $this->actingAs($this->partner1)->delete(route('memories.destroy', $memory));
        $response->assertRedirect(route('memories.index'));

        $this->assertDatabaseMissing('memories', ['id' => $memory->id]);
        Storage::disk('local')->assertMissing($filePath);
    }
}
