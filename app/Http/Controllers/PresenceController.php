<?php

namespace App\Http\Controllers;

use App\Models\Presence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PresenceController extends Controller
{
    public function index()
    {
        if (Auth::user()->role === 'admin') {
            $presences = Presence::with('user')->latest()->get();
        } else {
            $presences = Presence::where('user_id', Auth::id())->latest()->get();
        }
        return view('presence.index', compact('presences'));
    }

    public function create()
    {
        // Cek apakah sudah absen hari ini
        $todayPresence = Presence::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($todayPresence) {
            return redirect()->route('user.home')->with('error', 'Anda sudah melakukan absen masuk hari ini!');
        }

        return view('presence.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|in:hadir,izin,sakit',
            'foto'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Cek duplikasi absen
        $todayPresence = Presence::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::today())
            ->first();

        if ($todayPresence) {
            return redirect()->route('user.home')->with('error', 'Anda sudah melakukan absen hari ini!');
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('absensi', 'public');
        }

        Presence::create([
            'user_id' => Auth::id(),
            'status' => $request->status,
            'jam_masuk' => Carbon::now()->format('H:i:s'),
            'foto' => $fotoPath,
        ]);

        return redirect()->route('user.home')->with('success', 'Absensi masuk berhasil disimpan!');
    }

    public function edit($id)
    {
        $presence = Presence::findOrFail($id);
        
        // Validasi: hanya pemilik yang bisa edit
        if ($presence->user_id !== Auth::id()) {
            return redirect()->route('user.home')->with('error', 'Akses ditolak!');
        }

        // Validasi: sudah absen pulang?
        if ($presence->jam_keluar) {
            return redirect()->route('user.home')->with('error', 'Anda sudah melakukan absen pulang!');
        }

        return view('presence.edit', compact('presence'));
    }

    public function update(Request $request, $id)
    {
        $presence = Presence::findOrFail($id);
        
        // Validasi kepemilikan
        if ($presence->user_id !== Auth::id()) {
            return redirect()->route('user.home')->with('error', 'Akses ditolak!');
        }

        // Validasi sudah pulang
        if ($presence->jam_keluar) {
            return redirect()->route('user.home')->with('error', 'Anda sudah melakukan absen pulang!');
        }

        $presence->update([
            'jam_keluar' => Carbon::now()->format('H:i:s')
        ]);

        return redirect()->route('user.home')->with('success', 'Absensi pulang berhasil disimpan!');
    }

    public function destroy(Presence $presence)
    {
        $presence->delete();
        return redirect()->route('presence.index')->with('success', 'Data absensi berhasil dihapus.');
    }
}