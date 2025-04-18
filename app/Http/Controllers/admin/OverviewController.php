<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class OverviewController extends Controller
{
    public function OverviewIndex()
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            abort(403, 'Unauthorized action.');
        }

        $studentCount = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        $users = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        });

        if (request()->ajax()) {
            return DataTables::of($users)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<div class="btn-group" role="group" aria-label="Basic example">
                        <button class="btn btn-sm btn-outline-warning edit-btn" data-id="' . $row->id . '">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-info view-btn" data-id="' . $row->id . '">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-btn" data-id="' . $row->id . '">
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
                ->addColumn('password', function ($row) {
                    return $row->password_plain ?? '-';
                })
                ->addColumn('rata_rata', function ($row) {
                    return $row->rata_rata ?? '-';
                })
                ->addColumn('status', function ($row) {
                    $badgeClass = $row->status === 'Lulus' ? 'badge bg-success' : 'badge bg-danger';
                    return '<span class="' . $badgeClass . '">' . ucfirst($row->status ?? 'inactive') . '</span>';
                })
                ->rawColumns(['action', 'foto', 'status', 'password', 'rata_rata'])
                ->make(true);
        }

        return view('admin.overview', compact('studentCount'));
    }

    public function OverviewStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nisn' => 'required|string|max:16|unique:users,nisn',
            'password' => 'required|string|min:4',
            'jurusan' => 'nullable|string',
            'rata_rata' => 'nullable|numeric|between:0,100',
            'status' => 'nullable|in:Lulus,Tidak Lulus',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $userData = $request->only(['name', 'nisn', 'jurusan', 'rata_rata', 'status']);
        $userData['status'] = $userData['status'] ?? 'inactive';
        $userData['password'] = bcrypt($request->password);
        $userData['password_plain'] = $request->password;

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
            'nisn' => 'required|string|max:16|unique:users,nisn,' . $user->id,
            'password' => 'nullable|string|min:4',
            'jurusan' => 'nullable|string',
            'rata_rata' => 'nullable|numeric|between:0,100',
            'status' => 'nullable|in:Lulus,Tidak Lulus',
            'foto_diri' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $userData = $request->only(['name', 'nisn', 'jurusan', 'rata_rata', 'status']);
        $userData['status'] = $userData['status'] ?? $user->status;

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
            $userData['password_plain'] = $request->password;
        }

        if ($request->hasFile('foto_diri')) {
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

        if ($user->foto_diri) {
            Storage::disk('public')->delete($user->foto_diri);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new UsersImport, $request->file('file'));
            return response()->json(['message' => 'Data imported successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroyAll()
    {
        if (!Auth::user()->hasRole('admin')) {
            return response()->json(['message' => 'Unauthorized action.'], 403);
        }

        try {
            // Mulai transaction untuk memastikan konsistensi data
            \DB::beginTransaction();

            // Hapus semua foto terlebih dahulu
            $usersWithPhotos = User::whereNotNull('foto_diri')
                ->whereDoesntHave('roles', function ($query) {
                    $query->where('name', 'admin');
                })->get();

            foreach ($usersWithPhotos as $user) {
                Storage::disk('public')->delete($user->foto_diri);
            }

            // Hapus semua user kecuali admin
            $deletedCount = User::whereDoesntHave('roles', function ($query) {
                $query->where('name', 'admin');
            })->delete();

            \DB::commit();

            return response()->json([
                'message' => 'Semua data siswa berhasil dihapus',
                'deleted_count' => $deletedCount
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getStudentCount()
    {
        $count = User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        return response()->json(['count' => $count]);
    }

}
