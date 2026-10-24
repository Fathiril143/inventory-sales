<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Pembelian | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 1000px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('purchases.index') }}">← Kembali ke Pembelian</a></p>
    <h1>Detail Pembelian</h1>
    <p><strong>Nomor:</strong> {{ $purchase->purchase_number }}</p>
    <p><strong>Pemasok:</strong> {{ $purchase->supplier->name }}</p>
    <p><strong>Dibuat oleh:</strong> {{ $purchase->user->name }}</p>
    <p><strong>Tanggal:</strong> {{ $purchase->purchased_at->format('d/m/Y H:i') }}</p>
    <p><strong>Status:</strong> {{ ucfirst($purchase->status) }}</p>

    @if (session('success'))
        <p style="padding: .8rem; background: #dcfce7; color: #166534;">{{ session('success') }}</p>
    @endif
    @error('purchase')
        <p style="padding: .8rem; background: #fee2e2; color: #991b1b;">{{ $message }}</p>
    @enderror
    @if (auth()->user()->role === 'admin' && $purchase->status === 'completed')
        <form method="POST" action="{{ route('purchases.cancel', $purchase) }}" onsubmit="return confirm('Batalkan pembelian ini dan kurangi stok yang masuk?')">
            @csrf
            <button type="submit">Batalkan Pembelian</button>
        </form>
    @endif

    <h2>Item pembelian</h2>
    <table style="width: 100%; border-collapse: collapse; text-align: left;">
        <thead><tr><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">SKU</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Produk</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Jumlah</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Harga per unit</th><th style="padding: .7rem; border-bottom: 1px solid #cbd5e1;">Subtotal</th></tr></thead>
        <tbody>
            @foreach ($purchase->items as $item)
                <tr><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $item->product->sku }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $item->product->name }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">{{ $item->quantity }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">Rp {{ number_format((float) $item->unit_cost, 0, ',', '.') }}</td><td style="padding: .7rem; border-bottom: 1px solid #e2e8f0;">Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}</td></tr>
            @endforeach
        </tbody>
    </table>
    <h2>Total: Rp {{ number_format((float) $purchase->total, 0, ',', '.') }}</h2>
</body>
</html>
