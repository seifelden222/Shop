<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{

            $products = Product::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            return view('products.index', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching products.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('products.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the create product form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('main_image')) {
                $img_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
                $path = $request->file('main_image')->store('products', 'public');
                $validated['main_image'] = $path;
            }
            Product::create($validated);
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the product.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $products = Product::findOrFail($id);
            return view('products.show', compact('products'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching the product details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try{

            $products = Product::findOrFail($id);
            return view('products.edit', compact('products'));
        }catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the edit product form.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('main_image')) {
                if($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $img_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
                $path = $request->file('main_image')->store('products', 'public');
                $validated['main_image'] = $path;
            }
            $product->update($validated);
            return redirect()->route('products.index')->with('success', 'Product updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the product.');
        }       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $products = Product::findOrFail($id);
            $products->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the product.');
        }
    }
}
