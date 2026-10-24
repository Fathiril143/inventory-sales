<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PurchaseController extends Controller
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

        $query = Purchase::with(['supplier', 'user'])->withCount('items');

        if (! empty($filters['date_from'])) $query->whereDate('purchased_at', '>=', $filters['date_from']);
        if (! empty($filters['date_to'])) $query->whereDate('purchased_at', '<=', $filters['date_to']);
        if (! empty($filters['status'])) $query->where('status', $filters['status']);

        $purchases = $query->latest('purchased_at')->paginate(10)->withQueryString();

        return view('purchases.index', compact('purchases', 'filters'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products = Product::orderBy('name')->get(['id', 'sku', 'name', 'purchase_price']);

        return view('purchases.create', compact('suppliers', 'products'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'distinct', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.unit_cost' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
        ]);

        $items = collect($validated['items'])->sortBy('product_id');

        $purchase = DB::transaction(function () use ($validated, $items, $request) {
            $purchase = Purchase::create([
                'supplier_id' => $validated['supplier_id'],
                'user_id' => $request->user()->id,
                'purchase_number' => 'PB-'.now()->format('YmdHis').'-'.Str::upper(Str::random(4)),
                'total' => 0,
                'status' => 'completed',
                'purchased_at' => now(),
            ]);

            $totalCents = 0;

            foreach ($items as $item) {
                $product = Product::whereKey($item['product_id'])->lockForUpdate()->firstOrFail();
                $quantity = (int) $item['quantity'];
                $unitCostCents = (int) round((float) $item['unit_cost'] * 100);
                $subtotalCents = $unitCostCents * $quantity;

                $purchase->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_cost' => number_format($unitCostCents / 100, 2, '.', ''),
                    'subtotal' => number_format($subtotalCents / 100, 2, '.', ''),
                ]);

                $product->increment('stock', $quantity);
                $totalCents += $subtotalCents;
            }

            $purchase->update(['total' => number_format($totalCents / 100, 2, '.', '')]);

            return $purchase;
        });

        return redirect()
            ->route('purchases.index')
            ->with('success', "Pembelian {$purchase->purchase_number} berhasil disimpan; stok produk sudah ditambahkan.");
    }

    public function show(Purchase $purchase): View
    {
        $purchase->load(['supplier', 'user', 'items.product']);

        return view('purchases.show', compact('purchase'));
    }

    public function cancel(Purchase $purchase): RedirectResponse
    {
        DB::transaction(function () use ($purchase) {
            $lockedPurchase = Purchase::whereKey($purchase->id)->lockForUpdate()->firstOrFail();

            if ($lockedPurchase->status !== 'completed') {
                throw ValidationException::withMessages(['purchase' => 'Transaksi ini sudah dibatalkan.']);
            }

            $items = $lockedPurchase->items()->orderBy('product_id')->get();

            foreach ($items as $item) {
                $product = Product::whereKey($item->product_id)->lockForUpdate()->firstOrFail();

                if ($product->stock < $item->quantity) {
                    throw ValidationException::withMessages([
                        'purchase' => "Pembelian tidak dapat dibatalkan karena stok {$product->name} sudah terpakai.",
                    ]);
                }

                $product->decrement('stock', $item->quantity);
            }

            $lockedPurchase->update(['status' => 'cancelled']);
        });

        return redirect()->route('purchases.show', $purchase)->with('success', 'Pembelian dibatalkan dan stok disesuaikan.');
    }
}
