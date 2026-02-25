<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;



class DashboardController extends Controller
{
    public function stats() {
        $totalProducts = Product::count();
        $totalValue = Product::selectRaw('SUM(quantity * price) as total')
            ->value('total');
        $lowStockCount = Product::whereColumn('quantity','<','min_stock')->count();
        $movementsToday = StockMovement::whereDate('created_at', now()->toDateString())->count();

        return response()->json([
            'totalProducts'=>$totalProducts,
            'totalValue'=> (float)$totalValue,
            'lowStockCount'=>$lowStockCount,
            'movementsToday'=>$movementsToday,
        ]);
    }
}
