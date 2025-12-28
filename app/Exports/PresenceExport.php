<?php

namespace App\Exports;

use App\Models\Presence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PresenceExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Presence::with('user')->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Tanggal Absen',
            'Jam Masuk',
            'Jam Keluar',
            'Status',
            'Foto (path)',
        ];
    }

    public function map($presence): array
{
    // parsing string ke Carbon
    $jamMasuk = $presence->jam_masuk ? Carbon::parse($presence->jam_masuk) : null;
    $jamKeluar = $presence->jam_keluar ? Carbon::parse($presence->jam_keluar) : null;

    return [
        $presence->id,
        $presence->user->name ?? '-',                  // Nama karyawan
        $jamMasuk ? $jamMasuk->format('Y-m-d') : '-', // Tanggal absen
        $jamMasuk ? $jamMasuk->format('H:i:s') : '-', // Jam masuk
        $jamKeluar ? $jamKeluar->format('H:i:s') : '-', // Jam keluar
        $presence->status ?? '-',                      // Status
        $presence->foto ?? '-',                        // Foto path
    ];
}
}
