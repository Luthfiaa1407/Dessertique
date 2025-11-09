<?php

namespace App\Http\Controllers;

use App\Models\categories;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // Tampilkan semua produk
    public function index()
    {
        try {
            $products = Product::with('category')->latest()->get();
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memuat data: ' . $e->getMessage()]);
        }
    }

    // Form tambah produk (admin only)
    public function create()
    {
        if (!session('user')) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
        }

        if (session('user.role') !== 'admin') {
            abort(403);
        }

        $categories = categories::all();
        return view('products.create', compact('categories'));
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            if (session('user.role') !== 'admin') {
                abort(403);
            }

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            Product::create($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menambah produk: ' . $e->getMessage()])->withInput();
        }
    }

    // Detail produk
    public function show(Product $product)
    {
        try {
            $product->load('category');
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memuat detail: ' . $e->getMessage()]);
        }
    }

    // Form edit produk (admin only)
    public function edit(Product $product)
    {
        if (!session('user')) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
        }

        if (session('user.role') !== 'admin') {
            abort(403);
        }

        $categories = categories::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Update produk (admin only)
    public function update(Request $request, Product $product)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            if (session('user.role') !== 'admin') {
                abort(403);
            }

            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'price' => 'required|numeric|min:0',
                'stock' => 'required|integer|min:0',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            $data = $request->all();

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $data['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($data);

            return redirect()->route('products.index')->with('success', 'Produk berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal update produk: ' . $e->getMessage()])->withInput();
        }
    }

    // Hapus produk (admin only)
    public function destroy(Product $product)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            if (session('user.role') !== 'admin') {
                abort(403);
            }

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();

            return redirect()->route('products.index')->with('success', 'Produk berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal hapus produk: ' . $e->getMessage()]);
        }
    }
}
