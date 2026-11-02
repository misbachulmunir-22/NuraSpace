<?php

namespace Tests\Feature;

use App\Models\Couple;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WishlistTest extends TestCase
{
    use RefreshDatabase;

    protected Couple $couple;
    protected User $partner1;
    protected User $partner2;
    protected User $child;

    protected function setUp(): void
    {
        parent::setUp();

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

    public function test_child_cannot_access_wishlist(): void
    {
        $response = $this->actingAs($this->child)->get(route('wishlists.index'));

        $response->assertStatus(403);
    }

    public function test_partner_can_create_wishlist_item(): void
    {
        $response = $this->actingAs($this->partner1)->post(route('wishlists.store'), [
            'title' => 'Liburan ke Raja Ampat',
            'category' => 'travel',
            'estimated_cost' => 25000000,
            'target_date' => '2027-06-01',
            'description' => 'Menikmati sunset dan menyelam bersama di laut biru.',
        ]);

        $response->assertRedirect(route('wishlists.index'));
        $response->assertSessionHas('status');

        $this->assertDatabaseHas('wishlists', [
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Liburan ke Raja Ampat',
            'category' => 'travel',
            'estimated_cost' => 25000000,
            'status' => 'pending',
        ]);
    }

    public function test_partner_can_toggle_status_to_achieved(): void
    {
        $wishlist = Wishlist::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Beli Meja Kerja Kayu Jati',
            'category' => 'item',
            'estimated_cost' => 5000000,
            'status' => 'in_progress',
        ]);

        $this->assertNull($wishlist->achieved_at);

        $response = $this->actingAs($this->partner2)->patch(route('wishlists.status', $wishlist), [
            'status' => 'achieved',
        ]);

        $response->assertRedirect(route('wishlists.index'));

        $wishlist->refresh();
        $this->assertEquals('achieved', $wishlist->status);
        $this->assertNotNull($wishlist->achieved_at);
    }

    public function test_partner_can_delete_wishlist_item(): void
    {
        $wishlist = Wishlist::create([
            'couple_id' => $this->couple->id,
            'user_id' => $this->partner1->id,
            'title' => 'Rencana Lama',
            'category' => 'other',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->partner1)->delete(route('wishlists.destroy', $wishlist));

        $response->assertRedirect(route('wishlists.index'));
        $this->assertDatabaseMissing('wishlists', ['id' => $wishlist->id]);
    }
}
