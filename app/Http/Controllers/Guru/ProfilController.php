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

        $request->validate([
            'photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'current_password' => 'nullable|required_with:new_password|current_password',
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        if ($request->hasFile('photo')) {
            if ($user->photo_profile) {
                Storage::disk('public')->delete($user->photo_profile);
            }
            $path = $request->file('photo')->store('uploads/profile', 'public');
            $user->update(['photo_profile' => $path]);
        }

        if ($request->filled('new_password')) {
            $user->update([
                'password' => bcrypt($request->new_password)
            ]);
        }

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
