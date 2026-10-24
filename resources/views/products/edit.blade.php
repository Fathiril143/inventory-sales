<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Produk | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 700px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('products.index') }}">← Kembali ke Produk</a></p>
    <h1>Ubah Produk</h1>

    <form method="POST" action="{{ route('products.update', $product) }}">
        @csrf
        @method('PUT')

        <label for="category_id" style="display: block; margin: 1rem 0 .4rem;">Kategori</label>
        <select id="category_id" name="category_id" required style="box-sizing: border-box; width: 100%; padding: .7rem;">
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @error('category_id') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="sku" style="display: block; margin: 1rem 0 .4rem;">SKU</label>
        <input id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required maxlength="50" style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('sku') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="name" style="display: block; margin: 1rem 0 .4rem;">Nama produk</label>
        <input id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="150" style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('name') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="description" style="display: block; margin: 1rem 0 .4rem;">Deskripsi (opsional)</label>
        <textarea id="description" name="description" rows="3" maxlength="1000" style="box-sizing: border-box; width: 100%; padding: .7rem;">{{ old('description', $product->description) }}</textarea>
        @error('description') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="purchase_price" style="display: block; margin: 1rem 0 .4rem;">Harga beli</label>
        <input id="purchase_price" name="purchase_price" type="number" step="0.01" min="0" value="{{ old('purchase_price', $product->purchase_price) }}" required style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('purchase_price') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="selling_price" style="display: block; margin: 1rem 0 .4rem;">Harga jual</label>
        <input id="selling_price" name="selling_price" type="number" step="0.01" min="0" value="{{ old('selling_price', $product->selling_price) }}" required style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('selling_price') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="stock" style="display: block; margin: 1rem 0 .4rem;">Stok</label>
        <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', $product->stock) }}" required style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('stock') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="minimum_stock" style="display: block; margin: 1rem 0 .4rem;">Batas minimum stok</label>
        <input id="minimum_stock" name="minimum_stock" type="number" min="0" value="{{ old('minimum_stock', $product->minimum_stock) }}" required style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('minimum_stock') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <button type="submit" style="margin-top: 1rem; padding: .7rem 1rem;">Simpan Perubahan</button>
    </form>
</body>
</html>
