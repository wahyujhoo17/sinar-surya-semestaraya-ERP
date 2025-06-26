<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $user = $request->user();

        // Load karyawan with related data
        $user->load([
            'karyawan.department',
            'karyawan.jabatan',
            'karyawan.user.roles'
        ]);

        $breadcrumbs = [
            ['name' => 'Dashboard', 'url' => route('dashboard')],
            ['name' => 'Profile', 'url' => null],
        ];

        return view('profile.edit', [
            'user' => $user,
            'breadcrumbs' => $breadcrumbs,
            'currentPage' => 'Profile'
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $request->user()->id],
        ]);

        $user = $request->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    /**
     * Update the employee's personal information.
     */
    public function updateEmployee(Request $request)
    {
        $user = $request->user();

        // Check if user has karyawan profile
        if (!$user->karyawan) {
            return redirect()->route('profile.edit')->with('error', 'Hanya karyawan yang dapat mengubah informasi personal.');
        }

        $request->validateWithBag('updateEmployee', [
            'nama_lengkap' => ['nullable', 'string', 'max:255'],
            'telepon' => ['nullable', 'string', 'max:20', 'regex:/^[0-9\+\-\(\)\s]+$/'],
            'alamat' => ['nullable', 'string', 'max:1000'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'tanggal_lahir' => ['nullable', 'date', 'before:today'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
        ]);

        // Update karyawan data
        $karyawan = $user->karyawan;
        $karyawan->nama_lengkap = $request->nama_lengkap;
        $karyawan->telepon = $request->telepon;
        $karyawan->alamat = $request->alamat;
        $karyawan->tempat_lahir = $request->tempat_lahir;
        $karyawan->tanggal_lahir = $request->tanggal_lahir;
        $karyawan->jenis_kelamin = $request->jenis_kelamin;
        $karyawan->save();

        return redirect()->route('profile.edit')->with('success', 'Informasi personal berhasil diperbarui.')->with('status', 'employee-updated');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request)
    {
        try {
            $request->validate([
                'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:5120'], // max 5MB
            ]);

            $user = $request->user();

            // Check if user has karyawan profile
            if (!$user->karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya karyawan yang dapat mengubah foto profil.'
                ], 403);
            }

            // Delete old photo if exists
            if ($user->karyawan->foto && Storage::disk('public')->exists($user->karyawan->foto)) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }

            // Store new photo
            $path = $request->file('photo')->store('karyawan/photos', 'public');

            // Update karyawan foto field
            $user->karyawan->foto = $path;
            $user->karyawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui.',
                'photo_url' => asset('storage/' . $path)
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'File tidak valid. ' . implode(' ', $e->validator->errors()->all())
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengupload foto.'
            ], 500);
        }
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request)
    {
        try {
            $user = $request->user();

            // Check if user has karyawan profile
            if (!$user->karyawan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya karyawan yang dapat menghapus foto profil.'
                ], 403);
            }

            // Check if photo exists
            if (!$user->karyawan->foto) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada foto untuk dihapus.'
                ], 404);
            }

            // Delete photo if exists
            if (Storage::disk('public')->exists($user->karyawan->foto)) {
                Storage::disk('public')->delete($user->karyawan->foto);
            }

            // Remove photo from database
            $user->karyawan->foto = null;
            $user->karyawan->save();

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus foto.'
            ], 500);
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
