<?php

namespace App\Http\Controllers;

use App\Models\ChildAccountApproval;
use App\Models\ChildAccountRequest;
use App\Models\ChildDeletionApproval;
use App\Models\ChildDeletionRequest;
use App\Models\User;
use App\Notifications\ChildAccountApprovalNeededNotification;
use App\Notifications\ChildAccountStatusNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class FamilyController extends Controller
{
    /**
     * Display family members and pending approval requests.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        if (!$user->isPartner()) {
            abort(403, 'Akses dibatasi hanya untuk partner utama.');
        }

        $couple = $user->couple;
        $partners = $couple->partners()->get();
        $children = $couple->children()->get();

        $pendingRequests = ChildAccountRequest::with(['requester', 'approvals.partner'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        $historyRequests = ChildAccountRequest::with(['requester', 'approvals.partner'])
            ->whereIn('status', ['approved', 'rejected'])
            ->latest()
            ->take(10)
            ->get();

        $deletionRequests = ChildDeletionRequest::with(['child', 'requester', 'approvals.partner'])
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('family.index', [
            'couple' => $couple,
            'partners' => $partners,
            'children' => $children,
            'pendingRequests' => $pendingRequests,
            'historyRequests' => $historyRequests,
            'deletionRequests' => $deletionRequests,
            'currentPartner' => $user,
        ]);
    }

    /**
     * Partner proposes a new child account request.
     */
    public function storeRequest(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner()) {
            abort(403, 'Hanya partner yang dapat mengajukan akun anak.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150', 'unique:users,email'],
            'birth_date' => ['required', 'date', 'before:today'],
            'gender' => ['required', 'in:male,female'],
            'initial_password' => ['required', 'string', Password::defaults()],
        ]);

        $childRequest = ChildAccountRequest::create([
            'couple_id' => $user->couple_id,
            'requested_by_user_id' => $user->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'birth_date' => $validated['birth_date'],
            'gender' => $validated['gender'],
            'initial_password' => Hash::make($validated['initial_password']),
            'status' => 'pending',
        ]);

        // Create approval rows for all partners in couple
        $partners = $user->couple->partners;
        foreach ($partners as $partner) {
            $isRequester = $partner->id === $user->id;

            ChildAccountApproval::create([
                'request_id' => $childRequest->id,
                'partner_user_id' => $partner->id,
                'status' => $isRequester ? 'approved' : 'pending',
                'responded_at' => $isRequester ? now() : null,
            ]);

            // Notify partner who still needs to decide
            if (!$isRequester) {
                $partner->notify(new ChildAccountApprovalNeededNotification($childRequest));
            }
        }

        return redirect()->route('family.index')->with(
            'success',
            "Permintaan akun anak untuk {$childRequest->name} berhasil diajukan. Menunggu persetujuan pasangan Anda."
        );
    }

    /**
     * Partner approves the child account request.
     */
    public function approve(Request $request, ChildAccountRequest $childAccountRequest): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $childAccountRequest->couple_id) {
            abort(403);
        }

        if (!$childAccountRequest->isPending()) {
            return redirect()->route('family.index')->with('error', 'Permintaan ini sudah tidak dalam status pending.');
        }

        $approval = ChildAccountApproval::firstOrCreate(
            [
                'request_id' => $childAccountRequest->id,
                'partner_user_id' => $user->id,
            ]
        );

        $approval->update([
            'status' => 'approved',
            'notes' => $request->input('notes'),
            'responded_at' => now(),
        ]);

        // Check if both partners in the couple have approved
        $totalPartners = $user->couple->partners()->count();
        $approvedCount = $childAccountRequest->approvals()->where('status', 'approved')->count();

        if ($approvedCount >= $totalPartners && $totalPartners >= 2) {
            // BOTH partners approved! Create the child account
            $childUser = User::create([
                'couple_id' => $childAccountRequest->couple_id,
                'name' => $childAccountRequest->name,
                'email' => $childAccountRequest->email,
                'password' => $childAccountRequest->initial_password,
                'role' => 'child',
                'gender' => $childAccountRequest->gender,
                'birth_date' => $childAccountRequest->birth_date,
                'is_active' => true,
                'must_change_password' => true,
            ]);

            $childAccountRequest->update([
                'status' => 'approved',
                'created_child_user_id' => $childUser->id,
            ]);

            // Notify all partners
            foreach ($user->couple->partners as $partner) {
                $partner->notify(new ChildAccountStatusNotification($childAccountRequest, 'approved', $user->name));
            }

            return redirect()->route('family.index')->with(
                'success',
                "Kedua partner telah menyetujui! Akun anak untuk {$childUser->name} berhasil dibuat dan kini aktif."
            );
        }

        return redirect()->route('family.index')->with(
            'success',
            'Persetujuan Anda telah dicatat. Menunggu persetujuan partner lainnya.'
        );
    }

    /**
     * Partner rejects the child account request.
     */
    public function reject(Request $request, ChildAccountRequest $childAccountRequest): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $childAccountRequest->couple_id) {
            abort(403);
        }

        if (!$childAccountRequest->isPending()) {
            return redirect()->route('family.index')->with('error', 'Permintaan ini sudah selesai diproses.');
        }

        $approval = ChildAccountApproval::firstOrCreate(
            [
                'request_id' => $childAccountRequest->id,
                'partner_user_id' => $user->id,
            ]
        );

        $approval->update([
            'status' => 'rejected',
            'notes' => $request->input('notes'),
            'responded_at' => now(),
        ]);

        $childAccountRequest->update([
            'status' => 'rejected',
        ]);

        // Notify other partner
        foreach ($user->couple->partners as $partner) {
            if ($partner->id !== $user->id) {
                $partner->notify(new ChildAccountStatusNotification($childAccountRequest, 'rejected', $user->name));
            }
        }

        return redirect()->route('family.index')->with(
            'info',
            "Permintaan akun anak untuk {$childAccountRequest->name} telah ditolak."
        );
    }

    /**
     * Partner activates or deactivates child account.
     */
    public function toggleActive(Request $request, User $child): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $child->couple_id || !$child->isChild()) {
            abort(403);
        }

        $child->update([
            'is_active' => !$child->is_active,
        ]);

        $statusLabel = $child->is_active ? 'diaktifkan kembali' : 'dinonaktifkan';

        return redirect()->route('family.index')->with(
            'success',
            "Akun anak {$child->name} berhasil {$statusLabel}."
        );
    }

    /**
     * Partner resets child account password.
     */
    public function resetChildPassword(Request $request, User $child): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $child->couple_id || !$child->isChild()) {
            abort(403);
        }

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $child->update([
            'password' => Hash::make($validated['password']),
            'must_change_password' => true,
        ]);

        return redirect()->route('family.index')->with(
            'success',
            "Password akun anak {$child->name} berhasil direset. Anak wajib mengganti password saat login berikutnya."
        );
    }

    /**
     * Partner proposes child account deletion (requires dual approval).
     */
    public function requestDeletion(Request $request, User $child): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $child->couple_id || !$child->isChild()) {
            abort(403);
        }

        $deletionRequest = ChildDeletionRequest::create([
            'couple_id' => $user->couple_id,
            'child_user_id' => $child->id,
            'child_name' => $child->name,
            'requested_by_user_id' => $user->id,
            'reason' => $request->input('reason', 'Penghapusan oleh partner'),
            'status' => 'pending',
        ]);

        foreach ($user->couple->partners as $partner) {
            $isRequester = $partner->id === $user->id;

            ChildDeletionApproval::create([
                'deletion_request_id' => $deletionRequest->id,
                'partner_user_id' => $partner->id,
                'status' => $isRequester ? 'approved' : 'pending',
                'responded_at' => $isRequester ? now() : null,
            ]);
        }

        return redirect()->route('family.index')->with(
            'success',
            "Permintaan penghapusan akun anak {$child->name} diajukan. Akun hanya akan dihapus jika kedua partner menyetujui."
        );
    }

    /**
     * Partner approves child account deletion.
     */
    public function approveDeletion(Request $request, ChildDeletionRequest $deletionRequest): RedirectResponse
    {
        $user = $request->user();

        if (!$user->isPartner() || $user->couple_id !== $deletionRequest->couple_id) {
            abort(403);
        }

        $approval = ChildDeletionApproval::where('deletion_request_id', $deletionRequest->id)
            ->where('partner_user_id', $user->id)
            ->firstOrFail();

        $approval->update([
            'status' => 'approved',
            'responded_at' => now(),
        ]);

        $approvedCount = $deletionRequest->approvals()->where('status', 'approved')->count();
        $totalPartners = $user->couple->partners()->count();

        if ($approvedCount >= $totalPartners && $totalPartners >= 2) {
            $child = $deletionRequest->child;
            $childName = $deletionRequest->child_name ?? ($child?->name ?? 'Anak');

            $deletionRequest->update([
                'status' => 'approved',
                'child_user_id' => null,
            ]);

            if ($child) {
                $child->delete();
            }

            return redirect()->route('family.index')->with(
                'success',
                "Kedua partner telah menyetujui. Akun anak {$childName} telah resmi dihapus dari ruang keluarga."
            );
        }

        return redirect()->route('family.index')->with(
            'success',
            'Persetujuan penghapusan akun telah dicatat. Menunggu persetujuan partner lainnya.'
        );
    }
}
