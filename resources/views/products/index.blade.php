<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Produk | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 1100px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('dashboard') }}">← Kembali ke Dashboard</a></p>
    <h1>Produk</h1>

    @if (session('success'))
        <p style="padding: .8rem; background: #dcfce7; color: #166534;">{{ session('success') }}</p>
    @endif
    @error('product')
        <p style="padding: .8rem; background: #fee2e2; color: #991b1b;">{{ $message }}</p>
    @enderror

    @if (auth()->user()->role === 'admin')
        <p><a href="{{ route('products.create') }}">+ Tambah Produk</a></p>
    @endif

    <form method="GET" action="{{ route('products.index') }}" style="margin: 1rem 0; display: flex; flex-wrap: wrap; gap: .6rem;">
        <input name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Cari SKU atau nama produk">
        <select name="category_id">
            <option value="">Semua kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(($filters['category_id'] ?? '') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        <button type="submit">Cari / Filter</button>
        <a href="{{ route('products.index') }}">Bersihkan</a>
    </form>

    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead>
            <tr>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">SKU</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Nama</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Kategori</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Harga Jual</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Stok</th>
                <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Batas Minimum</th>
                @if (auth()->user()->role === 'admin')
                    <th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $product->sku }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $product->name }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $product->category->name }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">Rp {{ number_format((float) $product->selling_price, 0, ',', '.') }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $product->stock }}</td>
                    <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $product->minimum_stock }}</td>
                    @if (auth()->user()->role === 'admin')
                        <td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">
                            <a href="{{ route('products.edit', $product) }}">Ubah</a>
                            <form method="POST" action="{{ route('products.destroy', $product) }}" style="display: inline;" onsubmit="return confirm('Hapus produk ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="{{ auth()->user()->role === 'admin' ? 7 : 6 }}" style="padding: 1rem;">Belum ada produk.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">{{ $products->links() }}</div>
</body>
</html>
