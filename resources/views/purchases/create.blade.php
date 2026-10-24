<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catat Pembelian | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 900px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('purchases.index') }}">← Kembali ke Pembelian</a></p>
    <h1>Catat Pembelian Stok</h1>

    @if ($errors->any())
        <div style="padding: .8rem; background: #fee2e2; color: #991b1b;">
            <p>Periksa kembali data berikut:</p>
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    @if ($suppliers->isEmpty() || $products->isEmpty())
        <p>Tambahkan setidaknya satu pemasok dan satu produk sebelum mencatat pembelian.</p>
    @else
        @php($formItems = old('items', [['product_id' => '', 'quantity' => 1, 'unit_cost' => '']]))
        <form method="POST" action="{{ route('purchases.store') }}">
            @csrf

            <label for="supplier_id" style="display: block; margin: 1rem 0 .4rem;">Pemasok</label>
            <select id="supplier_id" name="supplier_id" required style="padding: .7rem; min-width: 300px;">
                <option value="">Pilih pemasok</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>{{ $supplier->name }}</option>
                @endforeach
            </select>

            <h2>Produk yang dibeli</h2>
            <div id="purchase-items">
                @foreach ($formItems as $index => $item)
                    <div class="purchase-row" style="display: flex; flex-wrap: wrap; gap: .6rem; align-items: end; margin: .8rem 0; padding: .8rem; background: #f1f5f9;">
                        <label>Produk<br>
                            <select name="items[{{ $index }}][product_id]" class="product-select" required style="padding: .6rem; min-width: 260px;">
                                <option value="">Pilih produk</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}" data-cost="{{ $product->purchase_price }}" @selected(($item['product_id'] ?? '') == $product->id)>{{ $product->sku }} — {{ $product->name }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label>Jumlah<br>
                            <input name="items[{{ $index }}][quantity]" type="number" min="1" value="{{ $item['quantity'] ?? 1 }}" required style="padding: .6rem; width: 90px;">
                        </label>
                        <label>Harga beli per unit<br>
                            <input name="items[{{ $index }}][unit_cost]" class="unit-cost" type="number" min="0" step="0.01" value="{{ $item['unit_cost'] ?? '' }}" required style="padding: .6rem; width: 150px;">
                        </label>
                        <button type="button" class="remove-row">Hapus baris</button>
                    </div>
                @endforeach
            </div>

            <button type="button" id="add-row">+ Tambah Produk</button>
            <p>Stok akan bertambah setelah transaksi berhasil disimpan. Total dihitung oleh sistem.</p>
            <button type="submit" style="margin-top: 1rem; padding: .7rem 1rem;">Simpan Pembelian</button>
        </form>

        <template id="purchase-row-template">
            <div class="purchase-row" style="display: flex; flex-wrap: wrap; gap: .6rem; align-items: end; margin: .8rem 0; padding: .8rem; background: #f1f5f9;">
                <label>Produk<br>
                    <select name="items[__INDEX__][product_id]" class="product-select" required style="padding: .6rem; min-width: 260px;">
                        <option value="">Pilih produk</option>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}" data-cost="{{ $product->purchase_price }}">{{ $product->sku }} — {{ $product->name }}</option>
                        @endforeach
                    </select>
                </label>
                <label>Jumlah<br>
                    <input name="items[__INDEX__][quantity]" type="number" min="1" value="1" required style="padding: .6rem; width: 90px;">
                </label>
                <label>Harga beli per unit<br>
                    <input name="items[__INDEX__][unit_cost]" class="unit-cost" type="number" min="0" step="0.01" required style="padding: .6rem; width: 150px;">
                </label>
                <button type="button" class="remove-row">Hapus baris</button>
            </div>
        </template>

        <script>
            let nextItemIndex = {{ count($formItems) }};
            const itemList = document.getElementById('purchase-items');

            document.getElementById('add-row').addEventListener('click', () => {
                const html = document.getElementById('purchase-row-template').innerHTML
                    .replaceAll('__INDEX__', String(nextItemIndex++));
                itemList.insertAdjacentHTML('beforeend', html);
            });

            itemList.addEventListener('change', (event) => {
                if (event.target.matches('.product-select')) {
                    const selected = event.target.selectedOptions[0];
                    const costInput = event.target.closest('.purchase-row').querySelector('.unit-cost');
                    if (selected.dataset.cost) costInput.value = selected.dataset.cost;
                }
            });

            itemList.addEventListener('click', (event) => {
                if (event.target.matches('.remove-row') && itemList.querySelectorAll('.purchase-row').length > 1) {
                    event.target.closest('.purchase-row').remove();
                }
            });
        </script>
    @endif
</body>
</html>
