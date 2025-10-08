<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $query = Product::orderBy('created_at', 'desc');
            $user = Auth::user();
            if ($user && ! $user->isAdmin()) {
                $query->where('user_id', $user->id);
            }
            $products = $query->paginate(15)->withQueryString();
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
            $this->authorize('create', Product::class);
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
            // ProductRequest already authorizes, but double-check here for safety
            $this->authorize('create', Product::class);
            $validated = $request->validated();
            $user = auth()->user();
            if ($user) {
                $validated['user_id'] = $user->id;
            }
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
            $this->authorize('view', $products);
            return view('products.show', compact('products'));
        } catch (\Exception $e) {
            // Log the exception so we can inspect the real cause in storage/logs/laravel.log
            Log::error('ProductController@show exception: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => (string) $e,
            ]);

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
            $this->authorize('update', $products);
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
            $this->authorize('update', $product);
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
            $this->authorize('delete', $products);
            $products->delete();
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the product.');
        }
    }
}
