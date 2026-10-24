<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();

        $totalProducts = Product::count();
        $totalTransactions = Purchase::count() + Sale::count();
        $salesToday = Sale::whereDate('sold_at', $today)->sum('total');
        $salesThisMonth = Sale::whereBetween('sold_at', [$monthStart, now()])->sum('total');
        $lowStockProducts = Product::with('category')
            ->whereColumn('stock', '<=', 'minimum_stock')
            ->orderBy('stock')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalTransactions',
            'salesToday',
            'salesThisMonth',
            'lowStockProducts',
        ));
    }
}
