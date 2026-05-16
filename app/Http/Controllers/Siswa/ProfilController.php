<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $siswa = $user->siswa;
        return view('siswa.profil.edit', compact('user', 'siswa'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $extension = $file->getClientOriginalExtension();
            $timestamp = time();
            $path = "users/{$user->id}/profile-{$timestamp}.{$extension}";

            $supabase = new \App\Services\SupabaseStorageService(config('services.supabase.profile_bucket'));
            $oldPhoto = $user->photo_profile;

            $tempPath = $file->getPathname();
            $mimeType = $file->getMimeType() ?: 'image/jpeg';

            if ($supabase->upload($tempPath, $path, $mimeType)) {
                $user->update(['photo_profile' => $path]);
                
                // Hapus foto lama jika upload baru sukses
                if ($oldPhoto) {
                    if (str_starts_with($oldPhoto, 'users/')) {
                        $supabase->delete($oldPhoto);
                    } else {
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($oldPhoto);
                    }
                }
            } else {
                return back()->with('error', 'Gagal mengunggah foto profil ke Supabase.');
            }
        }

        if ($request->filled('new_password')) {
            $user->update([
                'password' => bcrypt($request->new_password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
