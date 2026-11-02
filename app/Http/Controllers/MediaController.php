<?php

namespace App\Http\Controllers;

use App\Models\MemoryMedia;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class MediaController extends Controller
{
    /**
     * Serve private memory media (image/video) via signed URL or couple-scoped auth.
     */
    public function serveMemory(Request $request, MemoryMedia $memoryMedia): StreamedResponse
    {
        // 1. Authorization: either signed URL or authenticated couple member
        $hasValidSignature = $request->hasValidSignature();
        $memory = $memoryMedia->memory()->withoutGlobalScopes()->first();
        $isAuthorizedUser = $request->user() && $memory && $request->user()->couple_id === $memory->couple_id;

        if (!$hasValidSignature && !$isAuthorizedUser) {
            abort(403, 'Akses media privat tidak diizinkan atau URL kedaluwarsa.');
        }

        if (!Storage::disk('local')->exists($memoryMedia->file_path)) {
            abort(404, 'File media tidak ditemukan.');
        }

        return Storage::disk('local')->response($memoryMedia->file_path);
    }

    /**
     * Serve private wishlist image via signed URL or couple-scoped auth.
     */
    public function serveWishlist(Request $request, int|string $wishlistId): StreamedResponse
    {
        $wishlist = Wishlist::withoutGlobalScopes()->findOrFail($wishlistId);

        $hasValidSignature = $request->hasValidSignature();
        $isAuthorizedUser = $request->user() && $request->user()->couple_id === $wishlist->couple_id;

        if (!$hasValidSignature && !$isAuthorizedUser) {
            abort(403, 'Akses media privat tidak diizinkan atau URL kedaluwarsa.');
        }

        if (!$wishlist->image_path || !Storage::disk('local')->exists($wishlist->image_path)) {
            abort(404, 'Foto impian tidak ditemukan.');
        }

        return Storage::disk('local')->response($wishlist->image_path);
    }
}
