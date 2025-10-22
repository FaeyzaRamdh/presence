<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presence;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    // Dashboard admin
    public function dashboard()
    {
        $allUsers = User::where('role', 'user')->get();

        // Ambil user_id yang sudah absen hari ini
        $presentTodayIds = Presence::whereDate('created_at', Carbon::today())
                            ->pluck('user_id')
                            ->toArray();

        // User yang belum absen
        $notPresentUsers = $allUsers->whereNotIn('id', $presentTodayIds);

        $totalUsers = $allUsers->count();
        $totalPresenceToday = $allUsers->count() - count($notPresentUsers);

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalPresenceToday',
            'notPresentUsers'
        ));
    }

    public function home()
{
    return view('user.home');
}


    // List semua karyawan
    public function index()
    {
        $users = User::where('role', 'user')->get();
        return view('admin.user.index', compact('users'));
    }

    // Form tambah karyawan
    public function create()
    {
        return view('admin.user.create');
    }

    // Simpan karyawan baru
  public function store(Request $request)
{
    $request->validate([
        'name'     => 'required',
        'email'    => 'required|email|unique:users',
        'password' => 'required|min:6'
    ]);

    $user = User::create([
        'name'     => $request->name,
        'email'    => $request->email,
        'password' => Hash::make($request->password),
        'role'     => 'user'
    ]);

    return $user
        ? redirect()->route('users.index')->with('success', 'Data berhasil disimpan')
        : redirect()->back()->with('error', 'Data gagal disimpan');
}


    // Form edit karyawan
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    // Update karyawan
    public function update(Request $request, string $id)
{
    $request->validate([
        'name'  => 'required',
        'email' => 'required|email|unique:users,email,' . $id,
    ]);

    $user = User::findOrFail($id);
    $user->name  = $request->name;
    $user->email = $request->email;
    
    if ($request->password) {
        $user->password = Hash::make($request->password);
    }
    
    $user->save();

    return redirect()->route('users.index')->with('success', 'Data berhasil diupdate');
}

// Hapus karyawan
public function destroy(string $id)
{
    User::where('id', $id)->delete();
    return redirect()->route('users.index')->with('success', 'Data berhasil dihapus');
    }
}
