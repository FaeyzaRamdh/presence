<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Presence;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Exports\UsersExport;
use Carbon\Carbon;

class UserController extends Controller
{
    // Dashboard admin
   public function dashboard()
{
    // Ambil semua user role 'user'
    $users = User::where('role', 'user')->get();
    $totalUsers = $users->count();

    // Hitung kehadiran hari ini
    $statusCounts = ['belum' => 0, 'sedang' => 0, 'selesai' => 0];
    $userStatuses = [];

    foreach ($users as $user) {
        $todayPresence = \App\Models\Presence::where('user_id', $user->id)
                            ->whereDate('jam_masuk', \Carbon\Carbon::today())
                            ->first();

        if (!$todayPresence) {
            $status = 'Belum Bekerja';
            $badgeClass = 'bg-secondary';
            $statusCounts['belum']++;
        } elseif ($todayPresence->jam_masuk && !$todayPresence->jam_keluar) {
            $status = 'Sedang Bekerja';
            $badgeClass = 'bg-warning text-dark';
            $statusCounts['sedang']++;
        } elseif ($todayPresence->jam_keluar) {
            $status = 'Selesai Bekerja';
            $badgeClass = 'bg-success';
            $statusCounts['selesai']++;
        }

        $userStatuses[$user->id] = ['status' => $status, 'badge' => $badgeClass];
    }

    $totalPresenceToday = $statusCounts['sedang'] + $statusCounts['selesai'];
    $attendancePercentage = $totalUsers > 0 ? ($totalPresenceToday / $totalUsers * 100) : 0;

    return view('admin.dashboard', compact(
        'users',
        'totalUsers',
        'statusCounts',
        'userStatuses',
        'totalPresenceToday',
        'attendancePercentage'
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

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'user'
        ]);

        return redirect()->route('users.index')->with('success', 'Data berhasil disimpan');
    }

    // Form edit karyawan
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.user.edit', compact('user'));
    }

    // Update karyawan
    public function update(Request $request, $id)
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

    // Soft delete (Recycle Bin)
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'Data berhasil dihapus');
    }

    // Halaman recycle bin
    public function trash()
    {
        $users = User::onlyTrashed()->get();
        return view('admin.user.trash', compact('users'));
    }

    // Restore user
    public function restore($id)
    {
        User::onlyTrashed()->findOrFail($id)->restore();
        return redirect()->route('users.trash')->with('success', 'Data berhasil dipulihkan');
    }

    // Hapus permanent
    public function forceDelete($id)
    {
        User::onlyTrashed()->findOrFail($id)->forceDelete();
        return redirect()->route('users.trash')->with('success', 'Data dihapus permanen');
    }

    // Export Excel
    public function export()
    {
        return Excel::download(new UsersExport, 'data_karyawan.xlsx');
    }

    // Export PDF
    public function exportPDF()
    {
        $users = User::where('role', 'user')->get();
        $pdf = Pdf::loadView('admin.user.pdf', compact('users'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('data_karyawan.pdf');
    }
}
