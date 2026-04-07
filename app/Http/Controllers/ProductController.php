<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->where('company_id', auth()->user()->company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products',
            'name' => 'required',
            'unit' => 'required',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'min_stock' => 'required|integer',
        ]);
        
        Product::create([
            'company_id' => auth()->user()->company_id,
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
            'reorder_point' => $request->reorder_point,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('products.index')->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('company_id', auth()->user()->company_id)
            ->where('is_active', true)
            ->get();
        
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku,' . $product->id,
            'name' => 'required',
            'unit' => 'required',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'min_stock' => 'required|integer',
        ]);
        
        $product->update([
            'category_id' => $request->category_id,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'name' => $request->name,
            'description' => $request->description,
            'unit' => $request->unit,
            'purchase_price' => $request->purchase_price,
            'selling_price' => $request->selling_price,
            'min_stock' => $request->min_stock,
            'max_stock' => $request->max_stock,
            'reorder_point' => $request->reorder_point,
            'is_active' => $request->has('is_active'),
        ]);
        
        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}