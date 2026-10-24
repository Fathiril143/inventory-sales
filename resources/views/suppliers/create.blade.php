<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Pemasok | Inventory Sales</title>
</head>
<body style="font-family: Arial, sans-serif; max-width: 700px; margin: 3rem auto; padding: 0 1rem; color: #172033;">
    <p><a href="{{ route('suppliers.index') }}">← Kembali ke Pemasok</a></p>
    <h1>Tambah Pemasok</h1>

    <form method="POST" action="{{ route('suppliers.store') }}">
        @csrf

        <label for="name" style="display: block; margin: 1rem 0 .4rem;">Nama pemasok</label>
        <input id="name" name="name" value="{{ old('name') }}" required maxlength="150" style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('name') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="phone" style="display: block; margin: 1rem 0 .4rem;">Nomor telepon (opsional)</label>
        <input id="phone" name="phone" value="{{ old('phone') }}" maxlength="30" style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('phone') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="email" style="display: block; margin: 1rem 0 .4rem;">Email (opsional)</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" maxlength="255" style="box-sizing: border-box; width: 100%; padding: .7rem;">
        @error('email') <p style="color: #b91c1c;">{{ $message }}</p> @enderror

        <label for="address" style="display: block; margin: 1rem 0 .4rem;">Alamat (opsional)</label>
        <textarea id="address" name="address" rows="3" maxlength="1000" style="box-sizing: border-box; width: 100%; padding: .7rem;">{{ old('address') }}</textarea>
        @error('address') <p style="color: #b91c1c;">{{ $message }}</p> @enderror
        <button type="submit" style="margin-top: 1rem; padding: .7rem 1rem;">Simpan Pemasok</button>
    </form>
</body>
</html>
