<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Dessertique') }}</title>

    <link 
        rel="stylesheet" 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
    >

    <style>
        body {
            background: #ffffff;
        }

        header, footer {
            background: #e9f7ff;
            border-color: #b9e3ff;
        }

        header {
            border-bottom: 2px solid #b9e3ff;
        }

        footer {
            border-top: 2px solid #b9e3ff;
        }

        a.nav-link {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }

        a.nav-link:hover {
            text-decoration: underline;
        }

        .brand {
            color: #007bff;
            font-weight: 700;
        }
    </style>
</head>

<body>
    <header>
        <div class="container d-flex justify-content-between align-items-center py-3">
            <h3 class="brand m-0">Dessertique</h3>

            <nav class="d-flex gap-4">
                @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('admin.users.index') }}" class="nav-link">Pengguna</a>
                    <a href="{{ route('admin.products.index') }}" class="nav-link">Produk</a>
                    <a href="{{ route('admin.categories.index') }}" class="nav-link">Kategori</a>
                    <a href="{{ route('admin.orders.index') }}" class="nav-link">Pesanan</a>
                    <a href="{{ route('logout') }}" class="nav-link">Logout</a>
                    @elseif(Auth::user()->role === 'customer')
                    <a href="{{ route('customer.dashboard') }}" class="nav-link">Dashboard</a>
                    <a href="{{ route('products.index') }}" class="nav-link">Semua Produk</a>
                    <a href="{{ route('categories.index') }}" class="nav-link">Kategori</a>
                    <a href="{{ route('orders.index') }}" class="nav-link">Pesanan Saya</a>
                    <a href="{{ route('customer.profile') }}" class="nav-link">Profile</a>
                    <a href="{{ route('logout') }}" class="nav-link">Logout</a>
                    @endif
                @endauth

                @guest
                    <a href="{{ url('/') }}" class="nav-link">Home</a>
                    <a href="{{ route('products.index') }}" class="nav-link">Produk</a>
                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                    <a href="{{ route('register.form') }}" class="nav-link">Daftar</a>
                @endguest
            </nav>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>

    <footer>
        <div class="container text-center py-3">
            <p class="m-0 fw-semibold text-primary">
                © {{ date('Y') }} Dessertique — All Rights Reserved.
            </p>
        </div>
    </footer>
</body>
</html>