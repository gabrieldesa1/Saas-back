<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Product::with('category')->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $req)
    {
        $data = $req->validate([
            'name'=>'required|string',
            'sku'=>'nullable|string',
            'category_id'=>'nullable|exists:categories,id',
            'quantity'=>'integer',
            'min_stock'=>'integer',
            'price'=>'numeric',
            'cost'=>'numeric',
        ]);
        $product = Product::create($data);
        return response()->json($product,201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Product::with('category', 'movements')->findOrFail($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $req, string $id)
    {
        $prod = Product::findOrFail($id);
        // $prod->update($req->all());
        $data = $req->validate([
            'name'=>'required|string',
            'sku'=>'nullable|string',
            'category_id'=>'nullable|exists:categories,id',
            'quantity'=>'integer',
            'min_stock'=>'integer',
            'price'=>'numeric',
            'cost'=>'numeric',
        ]);

        $prod->update($data);

        return response()->json($prod);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::destroy($id);
        return response()->json(null,204);
    }
}
