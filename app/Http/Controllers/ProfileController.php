<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Update user profile (Name & Avatar Photo).
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ], [
            'name.required' => 'Nama pengguna tidak boleh kosong.',
            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format foto profil harus JPG, PNG, atau WEBP.',
            'avatar.max' => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        $user->name = trim($request->input('name'));

        // Handle Avatar Upload
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $uploadDir = public_path('uploads/avatars');

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Remove previous avatar if it exists and is in uploads
            if ($user->avatar && str_starts_with($user->avatar, 'uploads/avatars/') && file_exists(public_path($user->avatar))) {
                @unlink(public_path($user->avatar));
            }

            $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($uploadDir, $fileName);
            $user->avatar = 'uploads/avatars/' . $fileName;
        }

        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui!',
                'name' => $user->name,
                'avatar_url' => $user->avatar_url,
            ]);
        }

        return back()->with('profile_success', 'Profil dan foto berhasil diperbarui!');
    }

    /**
     * Update user credentials (Email & Password for Login).
     */
    public function updateAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => ['nullable', 'string', 'min:6', 'confirmed'],
        ], [
            'email.required' => 'Email tidak boleh kosong.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah digunakan oleh akun lain.',
            'password.min' => 'Password minimal terdiri dari 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user->email = trim(strtolower($request->input('email')));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Email dan password login berhasil diperbarui!',
                'email' => $user->email,
            ]);
        }

        return back()->with('account_success', 'Email dan password login berhasil diperbarui! Gunakan informasi ini saat login.');
    }
}
