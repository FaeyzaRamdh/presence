<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil data user (karyawan) saja
     */
    public function collection()
    {
        return User::where('role', 'user')->get();
    }

    /**
     * Judul kolom Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama Karyawan',
            'Email',
            'Role',
            'Dibuat Pada',
            'Diperbarui Pada',
        ];
    }

    /**
     * Mapping tiap baris data
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name ?? '-',
            $user->email ?? '-',
            $user->role ?? '-',
            $user->created_at ? $user->created_at->format('Y-m-d H:i:s') : '-',
            $user->updated_at ? $user->updated_at->format('Y-m-d H:i:s') : '-',
        ];
    }
}
