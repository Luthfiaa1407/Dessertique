@extends('layout')

@section('title', 'Edit Produk')

@section('content')
<h2 class="mb-3">Edit Produk</h2>

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="card p-4 shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            @foreach($categories as $c)
                <option value="{{ $c->id }}" {{ $product->category_id == $c->id ? 'selected' : '' }}>
                    {{ $c->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Nama Produk</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Deskripsi</label>
        <textarea name="description" class="form-control" rows="3">{{ $product->description }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="price" class="form-control" value="{{ $product->price }}" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Stok</label>
        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required>
    </div>
    <div class="mb-3">
        <label class="form-label d-block">Gambar Sekarang</label>
        @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="" width="100">
        @else
            <span class="text-muted">Belum ada gambar</span>
        @endif
    </div>
    <div class="mb-3">
        <label class="form-label">Ganti Gambar (opsional)</label>
        <input type="file" name="image" class="form-control">
    </div>
    <div class="d-flex justify-content-between">
        <a href="/admin/products" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn btn-primary">Update</button>
    </div>
</form>
@endsection
