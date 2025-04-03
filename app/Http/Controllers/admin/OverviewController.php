<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OverviewController extends Controller
{
    public function OverviewIndex()
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        // Filter user yang tidak memiliki role admin
        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        });

        if (request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group" role="group" aria-label="Basic example">
                    <button class="btn btn-sm btn-warning edit-btn" data-id="' . $row->id . '">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-info view-btn" data-id="' . $row->id . '">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-sm btn-danger delete-btn" data-id="' . $row->id . '">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>';
                })
                ->addColumn('foto', function ($row) {
                    $photoUrl = $row->foto_diri
                        ? Storage::url($row->foto_diri)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($row->name) . '&background=random';

                    return '<img src="' . $photoUrl . '" class="rounded-square">';
                })
                ->addColumn('status', function ($row) {
                    $badgeClass = $row->status === 'Lulus' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . ucfirst($row->status ?? 'inactive') . '</span>';
                })
                ->rawColumns(['action', 'foto', 'status'])
                ->make(true);
        }

        return view('admin.overview');
    }

    public function OverviewStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis',
            'jurusan' => 'nullable|string',
            'status' => 'nullable|in:Lulus,Tidak Lulus',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $userData = $request->only(['name', 'nis', 'jurusan', 'status']);
        $userData['status'] = $userData['status'] ?? 'inactive';

        if ($request->hasFile('foto_diri')) {
            $path = $request->file('foto_diri')->store('users/photos', 'public');
            $userData['foto_diri'] = $path;
        }

        User::create($userData);

        return response()->json(['message' => 'User created successfully'], 201);
    }

    public function OverviewShow($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function OverviewEdit($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function OverviewUpdate(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'nis' => 'required|string|unique:users,nis,' . $user->id,
            'jurusan' => 'nullable|string',
            'status' => 'nullable|in:Lulus,Tidak Lulus',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $userData = $request->only(['name', 'nis', 'jurusan', 'status']);
        $userData['status'] = $userData['status'] ?? $user->status;

        if ($request->hasFile('foto_diri')) {
            // Delete old photo
            if ($user->foto_diri) {
                Storage::disk('public')->delete($user->foto_diri);
            }

            $path = $request->file('foto_diri')->store('users/photos', 'public');
            $userData['foto_diri'] = $path;
        }

        $user->update($userData);

        return response()->json(['message' => 'User updated successfully'], 200);
    }

    public function OverviewDestroy($id)
    {
        $user = User::findOrFail($id);

        // Delete photo
        if ($user->foto_diri) {
            Storage::disk('public')->delete($user->foto_diri);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
