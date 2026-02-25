<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
// use Illuminate\Container\Attributes\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $req)
    {
        $query = StockMovement::with([
            'product:id,name,sku',
            'user:id,name,email',
        ]);

        // filtro por produto
        if ($req->filled('product_id')) {
            $query->where('product_id', $req->product_id);
        }

        // filtro por tipo (entrada | saida)
        if($req->filled('type')) {
            $query->where('type', $req->type);
        }

        // ordenacao
        $query->orderBy('happened_at', 'desc');

        //paginacao
        $movements = $query->paginate(15);

        return response()->json($movements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $data = $req->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:entrada,saida',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
            'happened_at' => 'nullable|date',
    ]);

    return DB::transaction(function () use ($data) {

        $product = Product::lockForUpdate()->findOrFail($data['product_id']);

        // regra de negócio
        if ($data['type'] === 'saida' && $product->quantity < $data['quantity']) {
            return response()->json([
                'message' => 'Estoque insuficiente'
            ], 422);
        }

        $movement = StockMovement::create([
            'product_id' => $product->id,
            'user_id' => 1, // TEMPORARIO (depois volta auth)
            'type' => $data['type'],
            'quantity' => $data['quantity'],
            'reason' => $data['reason'] ?? null,
            'happened_at' => $data['happened_at'] ?? now(),
        ]);

        if ($data['type'] === 'entrada') {
            $product->increment('quantity', $data['quantity']);
        } else {
            $product->decrement('quantity', $data['quantity']);
        }

        return response()->json([
            'movement' => $movement,
            'product' => $product->fresh()
        ], 201);
    });
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
