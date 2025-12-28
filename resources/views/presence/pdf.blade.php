<!DOCTYPE html>
<html>
<head>
    <title>Data Absensi</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 5px; text-align: left; }
        th { background-color: #f2f2f2; }
        img { width: 50px; height: auto; }
    </style>
</head>
<body>
    <h2>Data Absensi Pegawai</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Status</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Foto</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presences as $key => $presence)
            <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $presence->user->name }}</td>
                <td>{{ ucfirst($presence->status) }}</td>
                <td>{{ $presence->jam_masuk }}</td>
                <td>{{ $presence->jam_keluar ?? '-' }}</td>
                <td>
                    @if($presence->foto)
                        <img src="{{ public_path($presence->foto) }}" alt="foto">
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
