<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class AdminAccountController extends Controller
{
    public function AdminAccountIndex()
    {
        $adminRole = Role::where('name', 'admin')->first();
        $adminUsers = $adminRole->users;

        return view('admin.AdminAccount', compact('adminUsers'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'password_plain' => 'required|string',
            'nisn' => 'required|string'
        ]);

        $user->update([
            'name' => $request->name,
            'password_plain' => $request->password_plain,
            'nisn' => $request->nisn,
            'password' => bcrypt($request->password_plain)
        ]);

        return back()->with('success', 'Data berhasil di Update 😎');
    }

    public function uploadPhoto(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Delete old photo if exists
        if ($user->foto_diri) {
            Storage::disk('public')->delete($user->foto_diri);
        }

        // Store the new photo
        $path = $request->file('photo')->store('profile-photos', 'public');

        $user->update([
            'foto_diri' => $path
        ]);

        return back()->with('success', 'Foto telah berhasil di Update 😁');
    }

}
