@extends('layout')

@section('title', 'Edit Kategori')

@section('content')
<div class="card">
    <div class="card-header bg-dark text-white">
        <h4>Edit Kategori</h4>
    </div>

    <div class="card-body">
        <!-- Ubah ke admin.categories.update -->
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
            </div>

            <button class="btn btn-primary">Update</button>
            <!-- Ubah link kembali juga -->
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection