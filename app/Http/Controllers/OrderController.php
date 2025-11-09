<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;

class OrderController extends Controller
{
    // Tampilkan daftar pesanan
    public function index()
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            $user = session('user');

            if ($user['role'] === 'admin') {
                $orders = Order::with(['user', 'product'])->latest()->get();
            } else {
                $orders = Order::where('user_id', $user['id'])->with('product')->latest()->get();
            }

            return view('orders.index', compact('orders'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memuat data: ' . $e->getMessage()]);
        }
    }

    // Form tambah pesanan
    public function create()
    {
        if (!session('user')) {
            return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
        }

        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    // Simpan pesanan baru
    public function store(Request $request)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'address' => 'required|string',
            ]);

            $product = Product::findOrFail($request->product_id);

            // Cek stok
            if ($product->stock < $request->quantity) {
                return back()->withErrors(['error' => 'Stok tidak mencukupi!'])->withInput();
            }

            $user = session('user');
            $total_price = $product->price * $request->quantity;

            // Buat pesanan
            Order::create([
                'user_id' => $user['id'],
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'total_price' => $total_price,
                'status' => 'pending',
                'address' => $request->address,
            ]);

            // Kurangi stok produk
            $product->decrement('stock', $request->quantity);

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membuat pesanan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Detail pesanan
    public function show(Order $order)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            $user = session('user');

            // Hanya admin atau pemilik pesanan yang boleh lihat
            if ($user['role'] !== 'admin' && $order->user_id !== $user['id']) {
                abort(403, 'Aksi tidak diizinkan.');
            }

            $order->load(['user', 'product']);
            return view('orders.show', compact('order'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memuat detail: ' . $e->getMessage()]);
        }
    }

    // Edit pesanan (admin only)
    public function edit(Order $order)
    {
        if (!session('user') || session('user.role') !== 'admin') {
            abort(403);
        }

        return view('orders.edit', compact('order'));
    }

    // Update status pesanan (admin only)
    public function update(Request $request, Order $order)
    {
        try {
            if (!session('user') || session('user.role') !== 'admin') {
                abort(403);
            }

            $request->validate([
                'status' => 'required|in:pending,processing,completed,cancelled',
            ]);

            $order->update(['status' => $request->status]);

            return redirect()->route('orders.index')->with('success', 'Status pesanan berhasil diupdate!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal update pesanan: ' . $e->getMessage()])
                ->withInput();
        }
    }

    // Hapus pesanan (admin only)
    public function destroy(Order $order)
    {
        try {
            if (!session('user') || session('user.role') !== 'admin') {
                abort(403);
            }

            $order->delete();

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menghapus pesanan: ' . $e->getMessage()]);
        }
    }

    // Batalkan pesanan (user only)
    public function cancel(Order $order)
    {
        try {
            if (!session('user')) {
                return redirect('/login')->withErrors(['error' => 'Silakan login terlebih dahulu!']);
            }

            $user = session('user');

            if ($order->user_id !== $user['id']) {
                abort(403, 'Aksi tidak diizinkan.');
            }

            if (!in_array($order->status, ['pending', 'processing'])) {
                return back()->withErrors(['error' => 'Pesanan sudah tidak bisa dibatalkan.']);
            }

            $order->update(['status' => 'cancelled']);

            $order->product->increment('stock', $order->quantity);

            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal membatalkan pesanan: ' . $e->getMessage()]);
        }
    }
}
