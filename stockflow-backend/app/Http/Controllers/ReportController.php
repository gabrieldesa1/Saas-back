<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index()
    {
        // Valor total do estoque
        $totalValue = Product::select(
            DB::raw('SUM(quantity * cost) as total')
        )->value('total') ?? 0;

        // Produtos ativos
        $activeProducts = Product::where('quantity', '>', 0)->count();

        // Margem média baseada nas vendas
        $averageMargin = StockMovement::where('type', 'saida')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->select(
                DB::raw('AVG((products.price - products.cost) / products.price * 100) as margin')
            )
            ->value('margin') ?? 0;

        // Total vendido no mês (quantidade)
        $monthlySales = StockMovement::where('type', 'saida')
            ->whereMonth('happened_at', Carbon::now()->month)
            ->sum('quantity');

        $totalStock = Product::sum('quantity') ?: 1;

        $stockTurnover = $monthlySales / $totalStock;

        // 🔥 Faturamento do mês (valor em dinheiro)
        $totalRevenue = StockMovement::where('type', 'saida')
            ->join('products', 'stock_movements.product_id', '=', 'products.id')
            ->whereMonth('happened_at', Carbon::now()->month)
            ->select(DB::raw('SUM(stock_movements.quantity * products.price) as revenue'))
            ->value('revenue') ?? 0;

        // Movimentos mensais
        $monthlyMovements = StockMovement::select(
                DB::raw('MONTH(happened_at) as month'),
                DB::raw('SUM(CASE WHEN type = "entrada" THEN quantity ELSE 0 END) as entries'),
                DB::raw('SUM(CASE WHEN type = "saida" THEN quantity ELSE 0 END) as exits')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Valor por categoria
        $categoryValue = Product::join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(products.quantity * products.cost) as value')
            )
            ->groupBy('categories.name')
            ->get();

        // Vendas semanais
        $weeklySales = StockMovement::where('type', 'saida')
            ->select(
                DB::raw('WEEK(happened_at) as week'),
                DB::raw('SUM(quantity) as total')
            )
            ->groupBy('week')
            ->orderBy('week')
            ->get();

        return response()->json([
            'totalValue' => round($totalValue, 2),
            'activeProducts' => $activeProducts,
            'averageMargin' => round($averageMargin, 2),
            'stockTurnover' => round($stockTurnover, 2),
            'totalRevenue' => round($totalRevenue, 2),

            'monthlyData' => $monthlyMovements->map(function ($item) {
                return [
                    'month' => $item->month,
                    'entradas' => $item->entries,
                    'saidas' => $item->exits,
                ];
            }),

            'categoryData' => $categoryValue,

            'trendData' => $weeklySales->map(function ($item) {
                return [
                    'day' => 'Semana ' . $item->week,
                    'valor' => $item->total,
                ];
            }),
        ]);
    }
}