<!DOCTYPE html>
<html>
<head>
    <title>Register - Dessertique</title>
</head>
<body>
    <h2>Register</h2>
    @if (session('success'))
        <p>{{ session('success') }}</p>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nama" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required><br>
        <button type="submit">Daftar</button>
    </form>

    <p>Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
</body>
</html>
