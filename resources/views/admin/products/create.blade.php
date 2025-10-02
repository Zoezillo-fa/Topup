@extends('layouts.admin')

@section('title', 'Tambah Produk Baru')

@section('content')
    <h2>Tambah Produk Baru</h2>
    <p>Isi formulir di bawah ini untuk menambahkan produk baru ke dalam sistem.</p>

    {{-- Arahkan form ke route 'admin.products.store' dengan metode POST --}}
    <form action="{{ route('admin.products.store') }}" method="POST">
        @csrf {{-- Token Keamanan Laravel --}}
        
        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" id="name" name="name" required>
        </div>
        
        <div class="form-group">
            <label for="product_code">Kode Produk (unik)</label>
            <input type="text" id="product_code" name="product_code" required>
        </div>
        
        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" id="price" name="price" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" rows="4"></textarea>
        </div>

        <button type="submit" class="btn-submit">Simpan Produk</button>
    </form>
@endsection