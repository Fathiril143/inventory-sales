<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ubah Kategori | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 640px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('categories.index') }}">← Kembali ke Kategori</a></p>
    <h1>Ubah Kategori</h1>

    <form method="POST" action="{{ route('categories.update', $category) }}">
        @csrf
        @method('PUT')

        <label for="name" style="display: block; margin: 1rem 0 .4rem;">Nama kategori</label>
        <input id="name" name="name" value="{{ old('name', $category->name) }}" required maxlength="100"
            style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('name') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="description" style="display: block; margin: 1rem 0 .4rem;">Deskripsi (opsional)</label>
        <textarea id="description" name="description" rows="4" maxlength="1000"
            style="box-sizing: border-box; width: 100%; padding: .7rem;">{{ old('description', $category->description) }}</textarea>
        @error('description') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <button type="submit" style="margin-top: 1rem; padding: .7rem 1rem;">Simpan Perubahan</button>
    </form>
</body>
</html>
