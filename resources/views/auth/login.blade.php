<!DOCTYPE html>
<html>
<head>
    <title>Login - Dessertique</title>
</head>
<body>
    <h2>Login</h2>
    @if (session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
    @if (session('success'))
        <p style="color:green">{{ session('success') }}</p>
    @endif

    <form action="{{ route('login') }}" method="POST">
        @csrf
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>

    <p>Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
</body>
</html>
