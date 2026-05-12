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
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $path = $request->file('photo')->store('uploads/profile', 'public');
            $user->update(['photo_profile' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
