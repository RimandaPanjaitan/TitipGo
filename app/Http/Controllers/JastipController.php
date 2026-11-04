<?php

namespace App\Http\Controllers;

use App\Models\JastipItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JastipController extends Controller
{
    /**
     * Menampilkan Katalog TitipGo dengan pencarian dinamis & paginasi.
     */
    public function index(Request $request)
    {
        $items = JastipItem::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('origin_city', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(12);

        return view('catalog.index', compact('items'));
    }

    /**
     * Transaksi Pemesanan Jastip Atomik (ACID Protection).
     */
    public function storeOrder(Request $request, int $id)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes'    => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $id) {
                // Lock row kuota item agar aman saat dibeli bersamaan pada milidetik yang sama
                $item = JastipItem::where('id', $id)->lockForUpdate()->firstOrFail();

                if ($item->quota < $validated['quantity']) {
                    throw new \RuntimeException("Kuota titipan untuk {$item->name} sudah habis!");
                }

                // Kurangi kuota secara atomik
                $item->decrement('quota', $validated['quantity']);

                // Simpan transaksi pemesanan
                Order::create([
                    'user_id'        => auth()->id(),
                    'jastip_item_id' => $item->id,
                    'quantity'       => $validated['quantity'],
                    'total_price'    => $item->price * $validated['quantity'],
                    'notes'          => $validated['notes'],
                    'status'         => 'pending',
                ]);
            }, 3);

            return back()->with('success', 'Titipan berhasil dipesan secara aman!');

        } catch (\RuntimeException $e) {
            return back()->withInput()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('[TITIPGO_ORDER_ERROR]: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Terjadi kesalahan sistem pada basis data.');
        }
    }
}