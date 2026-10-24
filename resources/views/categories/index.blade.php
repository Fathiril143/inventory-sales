<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kategori | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 960px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>
    <h1>Kategori Produk</h1>

    @if (session('success'))
        <p style="padding: .8rem; background: #dcfce7; color: #166534;">{{ session('success') }}</p>
    @endif
    @error('category')
        <p style="padding: .8rem; background: #fee2e2; color: #991b1b;">{{ $message }}</p>
    @enderror

    <p><a href="{{ route('categories.create') }}">+ Tambah Kategori</a></p>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Nama</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Deskripsi</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Jumlah Produk</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categories as $category)
                <tr>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $category->name }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $category->description ?: '—' }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $category->products_count }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">
                        <a href="{{ route('categories.edit', $category) }}">Ubah</a>
                        <form method="POST" action="{{ route('categories.destroy', $category) }}" style="display: inline;" onsubmit="return confirm('Hapus kategori ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" style="padding: 1rem;">Belum ada kategori. Tambahkan kategori pertama.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">{{ $categories->links() }}</div>
</body>
</html>
