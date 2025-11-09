@extends('layout')

@section('title', 'Dashboard Admin')

@section('content')
<div class="text-center mb-4">
    <h2>Selamat Datang, Admin {{ session('user')->name }}</h2>
    <p class="text-muted">Kelola semua data produk, kategori, dan pesanan di sini.</p>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h5>Kategori</h5>
                <p class="text-muted">Kelola kategori produk</p>
                <a href="{{ route('categories.index') }}" class="btn btn-dark btn-sm">Kelola</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h5>Produk</h5>
                <p class="text-muted">Tambah, ubah, atau hapus produk</p>
                <a href="{{ route('products.index') }}" class="btn btn-dark btn-sm">Kelola</a>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-3">
            <div class="card-body text-center">
                <h5>Pesanan</h5>
                <p class="text-muted">Lihat semua pesanan pelanggan</p>
                <a href="{{ route('orders.index') }}" class="btn btn-dark btn-sm">Kelola</a>
            </div>
        </div>
    </div>
</div>
@endsection
