<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Penjualan | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('sales.index') }}">← Kembali ke Penjualan</a></p>
    <h1>Catat Penjualan</h1>

    @if ($errors->any())
        <div style="padding: .8rem; background: #fee2e2; color: #991b1b;">
            <p>Periksa kembali data berikut:</p>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if ($products->isEmpty())
        <p>Tambahkan produk terlebih dahulu sebelum mencatat penjualan.</p>
    @else
        @php($formItems = old('items', [['product_id' => '', 'quantity' => 1]]))
        <form method="POST" action="{{ route('sales.store') }}">
            @csrf

            <p>Pilih produk dan jumlah. Harga diambil dari data produk, total dihitung oleh sistem.</p>
            <div id="sale-items">
                @foreach ($formItems as $index => $item)
                    <div class="sale-row" style="display: flex; flex-wrap: wrap; gap: .6rem; align-items: end; margin: .8rem 0; padding: .8rem; background: #f1f5f9;">
                        <label>Produk<br>
                            <select name="items[{{ $index }}][product_id]" class="product-select" required style="padding: .6rem; min-width: 300px;">
                                <option value="">Pilih produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}" @selected(($item['product_id'] ?? '') == $product->id)>{{ $product->sku }} — {{ $product->name }} (stok {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Jumlah<br>
                            <input name="items[{{ $index }}][quantity]" type="number" min="1" value="{{ $item['quantity'] ?? 1 }}" required style="padding: .6rem; width: 90px;">
                        </label>
                        <span class="price-display" style="padding: .6rem;">Pilih produk untuk melihat harga</span>
                        <button type="button" class="remove-row">Hapus baris</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-row">+ Tambah Produk</button>
            <p>Jika stok tidak cukup, seluruh transaksi akan ditolak dan stok tidak berubah.</p>
            <button type="submit" style="margin-top: 1rem; padding: .7rem 1rem;">Simpan Penjualan</button>
        </form>

        <template id="sale-row-template">
            <div class="sale-row" style="display: flex; flex-wrap: wrap; gap: .6rem; align-items: end; margin: .8rem 0; padding: .8rem; background: #f1f5f9;">
                <label>Produk<br>
                    <select name="items[__INDEX__][product_id]" class="product-select" required style="padding: .6rem; min-width: 300px;">
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" data-stock="{{ $product->stock }}">{{ $product->sku }} — {{ $product->name }} (stok {{ $product->stock }})</option>
                        @endforeach
                    </select>
                </label>
                <label>Jumlah<br>
                    <input name="items[__INDEX__][quantity]" type="number" min="1" value="1" required style="padding: .6rem; width: 90px;">
                </label>
                <span class="price-display" style="padding: .6rem;">Pilih produk untuk melihat harga</span>
                <button type="button" class="remove-row">Hapus baris</button>
            </div>
        </template>

        <script>
            let nextSaleItemIndex = {{ count($formItems) }};
            const saleItemList = document.getElementById('sale-items');

            function updatePrice(row) {
                const select = row.querySelector('.product-select');
                const option = select.selectedOptions[0];
                const display = row.querySelector('.price-display');
                if (!option || !option.dataset.price) {
                    display.textContent = 'Pilih produk untuk melihat harga';
                    return;
                }
                const price = Number(option.dataset.price);
                const stock = option.dataset.stock;
                display.textContent = `Harga: Rp ${new Intl.NumberFormat('id-ID').format(price)} | Stok: ${stock}`;
            }

            saleItemList.querySelectorAll('.sale-row').forEach(updatePrice);

            document.getElementById('add-row').addEventListener('click', () => {
                const html = document.getElementById('sale-row-template').innerHTML
                    .replaceAll('__INDEX__', String(nextSaleItemIndex++));
                saleItemList.insertAdjacentHTML('beforeend', html);
            });

            saleItemList.addEventListener('change', (event) => {
                if (event.target.matches('.product-select')) updatePrice(event.target.closest('.sale-row'));
            });

            saleItemList.addEventListener('click', (event) => {
                if (event.target.matches('.remove-row') && saleItemList.querySelectorAll('.sale-row').length > 1) {
                    event.target.closest('.sale-row').remove();
                }
            });
        </script>
    @endif
</body>
</html>
