<!DOCTYPE html>
<html lang="id">
<head>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Inventory Sales</title>
</head>
<body>
<main>
    <h1>Inventory &amp; Sales</h1>
    <p>Masuk untuk mengelola stok dan transaksi.</p>

    <form method="POST" action="{{ route('login.store') }}">
        @csrf

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        @error('email') <div class="error">{{ $message }}</div> @enderror

        <label for="password">Kata sandi</label>
        <input id="password" name="password" type="password" required autocomplete="current-password">

        <label class="remember"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
        <button type="submit">Masuk</button>
    </form>
</main>
</body>
</html>
