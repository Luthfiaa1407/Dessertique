@extends('layout')

@section('title', 'Tambah Kategori')

@section('content')
<div class="card">
    <div class="card-header bg-dark text-white">
        <h4>Tambah Kategori</h4>
    </div>
    <div class="card-body">
        <!-- ubah route ke admin.categories.store -->
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <button class="btn btn-primary">Simpan</button>
            <!-- ubah juga link kembali -->
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</div>
@endsection