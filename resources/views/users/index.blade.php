<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengguna | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>
    <h1>Pengguna</h1>

    @if (session('success'))
        <p style="padding: .8rem; background: #dcfce7; color: #166534;">{{ session('success') }}</p>
    @endif
    @error('user') <p style="padding: .8rem; background: #fee2e2; color: #991b1b;">{{ $message }}</p> @enderror

    <p><a href="{{ route('users.create') }}">+ Tambah Pengguna</a></p>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead><tr><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Nama</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Email</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Role</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Aksi</th></tr></thead>
        <tbody>
            @foreach ($users as $user)
                <tr><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $user->name }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $user->email }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ ucfirst($user->role) }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;"><a href="{{ route('users.edit', $user) }}">Ubah</a> <form method="POST" action="{{ route('users.destroy', $user) }}" style="display: inline;" onsubmit="return confirm('Hapus pengguna ini?')">@csrf @method('DELETE')<button type="submit">Hapus</button></form></td></tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 1rem;">{{ $users->links() }}</div>
</body>
</html>
