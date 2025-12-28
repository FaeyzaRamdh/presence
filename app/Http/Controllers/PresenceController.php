<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf; 
use App\Exports\PresenceExport;

use Carbon\Carbon;

class PresenceController extends Controller
{
    public function index()
    {
        $presences = Auth::user()->role === 'admin'
            ? Presence::with('user')->latest()->get()
            : Presence::where('user_id', Auth::id())->latest()->get();

        return view('presence.index', compact('presences'));
    }

    public function create()
    {
        $todayPresence = Presence::where('user_id', Auth::id())
            ->whereDate('jam_masuk', Carbon::today())
            ->first();

        if ($todayPresence) {
            return redirect()->route('presence.edit', $todayPresence->id)
                             ->with('error', 'Anda sudah melakukan absen masuk hari ini!');
        }

        return view('presence.create');
    }

   public function store(Request $request)
{
    $request->validate([
        'status' => 'required|in:hadir,izin,sakit',
        'foto'   => 'required|image|max:5120', // Maks 5MB
    ]);

    if ($request->hasFile('foto')) {
        $file = $request->file('foto');
        $filename = 'absensi_' . time() . '_' . Auth::id() . '.' . $file->getClientOriginalExtension();

        // Simpan langsung ke public/asset
        $file->move(public_path('asset'), $filename);

        $presence = Presence::create([
            'user_id' => Auth::id(),
            'status' => $request->status,
            'jam_masuk' => now()->format('H:i:s'),
            'foto' => 'asset/' . $filename, // untuk dipanggil pakai asset()
        ]);

     return response()->json([
    'success' => true,
    'redirect' => route('presence.index') 
]);

    }

    return response()->json(['success' => false, 'error' => 'Foto tidak ditemukan']);
}


        public function show($id)
{
    $presence = Presence::with('user')->findOrFail($id);
    return view('presence.show', compact('presence'));
}



    public function edit($id)
    {
        $presence = Presence::findOrFail($id);

        if ($presence->user_id !== Auth::id()) {
            return redirect()->route('presence.index')->with('error', 'Akses ditolak!');
        }

        return view('presence.edit', compact('presence'));
    }



    public function update($id)
    {
        $presence = Presence::findOrFail($id);

        if ($presence->user_id !== Auth::id()) {
            return redirect()->route('presence.index')->with('error', 'Akses ditolak!');
        }

        if ($presence->jam_keluar) {
            return redirect()->route('presence.index')->with('error', 'Anda sudah absen pulang!');
        }

        $presence->update([
            'jam_keluar' => now()->format('H:i:s'),
        ]);

        return redirect()->route('presence.index')->with('success', 'Absensi pulang berhasil disimpan!');
    }

    public function destroy(Presence $presence)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('presence.index')->with('error', 'Akses ditolak!');
        }

        $presence->delete();

        return redirect()->route('presence.index')->with('success', 'Data absensi berhasil dipindahkan ke Recycle Bin.');
    }

    public function trash()
    {
        $presences = Presence::onlyTrashed()->with('user')->get();
        return view('presence.trash', compact('presences'));
    }

    public function restore($id)
    {
        $presence = Presence::onlyTrashed()->findOrFail($id);
        $presence->restore();

        return redirect()->route('presence.trash')->with('success', 'data berhasil di restore');
    }

    public function forceDelete($id)
    {
        $presence = Presence::onlyTrashed()->findOrFail($id);

        if ($presence->foto && File::exists(public_path($presence->foto))){
            File::delete(public_path($presence->foto));
        }

        $presence->forceDelete();

        return redirect()->route('presence.trash')->with('success', 'data berhasil dihapus permanen');
    }

    public function export()
{
    return Excel::download(new PresenceExport, 'data_absensi.xlsx');
}
    public function exportPDF()
    {
        $presences = Auth::user()->role === 'admin'
        ? Presence::with('user')->latest()->get()
        : Presence::where('user_id', Auth::id())->latest()->get();

        $pdf = pdf::loadView('presence.pdf', compact('presences'));
        return $pdf->download('data_absensi.pdf');
    }
}
