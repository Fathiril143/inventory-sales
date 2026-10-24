<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <h1>Dashboard</h1>
    <p>Selamat datang, {{ auth()->user()->name }}. Role: {{ auth()->user()->role }}.</p>
    <h2>Ringkasan</h2>
    <ul>
        <li>Total produk: {{ $totalProducts }}</li>
        <li>Total transaksi pembelian dan penjualan: {{ $totalTransactions }}</li>
        <li>Penjualan hari ini: Rp {{ number_format((float) $salesToday, 0, ',', '.') }}</li>
        <li>Penjualan bulan ini: Rp {{ number_format((float) $salesThisMonth, 0, ',', '.') }}</li>
    </ul>

    <h2>Produk dengan stok rendah</h2>
    @if ($lowStockProducts->isEmpty())
        <p>Tidak ada produk di bawah batas minimum stok.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead><tr><th style="padding: .6rem; border-bottom: 1px solid #cbd5e1;">Produk</th><th style="padding: .6rem; border-bottom: 1px solid #cbd5e1;">Kategori</th><th style="padding: .6rem; border-bottom: 1px solid #cbd5e1;">Stok</th><th style="padding: .6rem; border-bottom: 1px solid #cbd5e1;">Minimum</th></tr></thead>
            <tbody>
                @foreach ($lowStockProducts as $product)
                    <tr><td style="padding: .6rem; border-bottom: 1px solid #e2e8f0;">{{ $product->name }}</td><td style="padding: .6rem; border-bottom: 1px solid #e2e8f0;">{{ $product->category->name }}</td><td style="padding: .6rem; border-bottom: 1px solid #e2e8f0;">{{ $product->stock }}</td><td style="padding: .6rem; border-bottom: 1px solid #e2e8f0;">{{ $product->minimum_stock }}</td></tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <h2>Menu</h2>
    <p><a href="{{ route('products.index') }}">Kelola produk</a></p>
    @if (auth()->user()->role === 'admin')
        <p><a href="{{ route('categories.index') }}">Kelola kategori produk</a></p>
        <p><a href="{{ route('suppliers.index') }}">Kelola pemasok</a></p>
        <p><a href="{{ route('users.index') }}">Kelola pengguna</a></p>
    @endif
    <p><a href="{{ route('purchases.index') }}">Kelola pembelian</a></p>
    <p><a href="{{ route('sales.index') }}">Kelola penjualan</a></p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Keluar</button>
    </form>
</body>
</html>
