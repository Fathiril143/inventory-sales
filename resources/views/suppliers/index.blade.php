<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pemasok | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 1100px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>
    <h1>Pemasok</h1>

    @if (session('success'))
        <p style="padding: .8rem; background: #dcfce7; color: #166534;">{{ session('success') }}</p>
    @endif
    @error('supplier')
        <p style="padding: .8rem; background: #fee2e2; color: #991b1b;">{{ $message }}</p>
    @enderror

    <p><a href="{{ route('suppliers.create') }}">+ Tambah Pemasok</a></p>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Nama</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Telepon</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Email</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Alamat</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Jumlah Pembelian</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($suppliers as $supplier)
                <tr>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $supplier->name }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $supplier->phone ?: '—' }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $supplier->email ?: '—' }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $supplier->address ?: '—' }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $supplier->purchases_count }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">
                        <a href="{{ route('suppliers.edit', $supplier) }}">Ubah</a>
                        <form method="POST" action="{{ route('suppliers.destroy', $supplier) }}" style="display: inline;" onsubmit="return confirm('Hapus pemasok ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="padding: 1rem;">Belum ada pemasok. Tambahkan pemasok pertama.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">{{ $suppliers->links() }}</div>
</body>
</html>
