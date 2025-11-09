@extends('layout')

@section('title', 'Dashboard Customer')

@section('content')
<div class="text-center mb-4">
    <h2>Selamat datang, {{ session('user.name') }}</h2>
    <p class="text-muted">Selamat datang di Dessertique! Yuk cek produk kami~</p>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h5>Produk Kami</h5>
                <p class="text-muted">Lihat semua menu dessert yang tersedia</p>
                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Lihat Produk</a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h5>Pesanan Saya</h5>
                <p class="text-muted">Lihat status pesanan yang telah kamu buat</p>
                <a href="{{ route('orders.index') }}" class="btn btn-dark btn-sm">Lihat Pesanan</a>
            </div>
        </div>
    </div>
</div>
@endsection
