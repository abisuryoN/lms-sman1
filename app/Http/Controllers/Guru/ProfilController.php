<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $guru = $user->guru;
        return view('guru.profil.edit', compact('user', 'guru'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $guru = $user->guru;

        $request->validate([
            'nama' => 'required|string|max:255',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $path = $request->file('photo')->store('uploads/profile', 'public');
            $user->update(['photo_profile' => $path]);
        }

        $user->update(['name' => $request->nama]);
        if ($guru) {
            $guru->update($request->only('nama', 'telepon', 'alamat'));
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
