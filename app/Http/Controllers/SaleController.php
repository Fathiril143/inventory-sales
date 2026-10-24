<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class SaleController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
            'status' => ['nullable', 'in:completed,cancelled'],
        ]);

        if (! empty($filters['date_from']) && ! empty($filters['date_to']) && $filters['date_from'] > $filters['date_to']) {
            return back()->withErrors(['date_to' => 'Tanggal akhir harus sama atau setelah tanggal awal.'])->withInput();
        }

        $query = Sale::with('user')->withCount('items');

        if (! empty($filters['date_from'])) $query->whereDate('sold_at', '>=', $filters['date_from']);
        if (! empty($filters['date_to'])) $query->whereDate('sold_at', '<=', $filters['date_to']);
        if (! empty($filters['status'])) $query->where('status', $filters['status']);

        $sales = $query->latest('sold_at')->paginate(10)->withQueryString();

        return view('sales.index', compact('sales', 'filters'));
    }

    public function create(): View
    {
        $products = Product::orderBy('name')->get(['id', 'sku', 'name', 'stock', 'selling_price']);

        return view('sales.create', compact('products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        $items = collect($validated['items'])->sortBy('product_id');

        $sale = DB::transaction(function () use ($items, $request) {
            $sale = Sale::create([
                'user_id' => $request->user()->id,
                'sale_number' => 'PJ-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'total' => 0,
                'status' => 'completed',
                'sold_at' => now(),
            ]);

            $totalCents = 0;

            foreach ($items as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();
                $quantity = (int) $item['quantity'];

                if ($quantity < 1 || $product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => "Stok {$product->name} tidak cukup. Stok tersedia: {$product->stock}.",
                    ]);
                }

                $unitPriceCents = (int) round((float) $product->selling_price * 100);
                $subtotalCents = $unitPriceCents * $quantity;

                $sale->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => number_format($unitPriceCents / 100, 2, '.', ''),
                    'subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                ]);

                $product->decrement('stock', $quantity);
                $totalCents += $subtotalCents;
            }

            $sale->update(['total' => number_format($totalCents / 100, 2, '.', '')]);

            return $sale;
        });

        return redirect()
            ->route('sales.index')
            ->with('success', "Penjualan {$sale->sale_number} berhasil disimpan.");
    }

    public function show(Sale $sale): View
    {
        $sale->load(['user', 'items.product']);

        return view('sales.show', compact('sale'));
    }

    public function cancel(Sale $sale): RedirectResponse
    {
        DB::transaction(function () use ($sale) {
            $lockedSale = Sale::whereKey($sale->id)->lockForUpdate()->firstOrFail();

            if ($lockedSale->status !== 'completed') {
                throw ValidationException::withMessages(['sale' => 'Transaksi ini sudah dibatalkan.']);
            }

            $items = $lockedSale->items()->orderBy('product_id')->get();

            foreach ($items as $item) {
                $product = Product::whereKey($item->product_id)->lockForUpdate()->firstOrFail();
                $product->increment('stock', $item->quantity);
            }

            $lockedSale->update(['status' => 'cancelled']);
        });

        return redirect()->route('sales.show', $sale)->with('success', 'Penjualan dibatalkan dan stok dikembalikan.');
    }
}
