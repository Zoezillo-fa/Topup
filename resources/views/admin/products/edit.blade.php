@extends('layouts.admin')

@section('title', 'Edit Produk')

@section('content')
    <h2>Edit Produk: {{ $product->name }}</h2>
    <p>Ubah data produk di bawah ini.</p>

    {{-- Arahkan form ke route 'admin.products.update' --}}
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST">
        @csrf           {{-- Token Keamanan Laravel --}}
        @method('PUT')   {{-- Trik untuk memberitahu Laravel kita menggunakan method PUT --}}
        
        <div class="form-group">
            <label for="name">Nama Produk</label>
            <input type="text" id="name" name="name" value="{{ $product->name }}" required>
        </div>
        
        <div class="form-group">
            <label for="product_code">Kode Produk (unik)</label>
            <input type="text" id="product_code" name="product_code" value="{{ $product->product_code }}" required>
        </div>
        
        <div class="form-group">
            <label for="price">Harga</label>
            <input type="number" id="price" name="price" value="{{ $product->price }}" required>
        </div>

        <div class="form-group">
            <label for="description">Deskripsi (Opsional)</label>
            <textarea id="description" name="description" rows="4">{{ $product->description }}</textarea>
        </div>

        <button type="submit" class="btn-submit">Update Produk</button>
    </form>
@endsection