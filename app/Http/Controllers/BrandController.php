<?php

namespace App\Http\Controllers;

use App\Http\Requests\BrandRequest;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $brands = Brand::orderBy('created_at', 'desc')->paginate(10)->withQueryString();
            return view('brands.index', compact('brands'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching brands.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('brands.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the create brand form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BrandRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                $img_name = time() . '_' . $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->store('brands', 'public');
                $validated['image'] = $path;
            }
            Brand::create($validated);
            return redirect()->route('brands.index')->with('success', 'Brand created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the brand.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $brands = Brand::findOrFail($id);
            return view('brands.show', compact('brands'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching the brands details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {

            $brands = Brand::findOrFail($id);
            return view('brands.edit', compact('brands'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the edit brands form.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BrandRequest $request, Brand $brands)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('image')) {
                if ($brands->image && Storage::disk('public')->exists($brands->image)) {
                    Storage::disk('public')->delete($brands->image);
                }
                $img_name = time() . '_' . $request->file('image')->getClientOriginalName();
                $path = $request->file('image')->store('brands', 'public');
                $validated['image'] = $path;
            }
            $brands->update($validated);
            return redirect()->route('brands.index')->with('success', 'Brand updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the brand.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $brands = Brand::findOrFail($id);
            $brands->delete();
            return redirect()->route('brands.index')->with('success', 'brands deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while deleting the brands.');
        }
    }

    public function TotalProducts($brandId = null)
    {
        $brand = Brand::where('is_active', true)->with(['products' => function ($query) {
            $query->where('status', 'published');
        }])->get();

        $brandData = [];
        foreach ($brand as $item) {
            $totalProducts = $item->products->count();
            $totalPrice = $item->products->sum('price');
            $averagePrice = $totalProducts > 0 ? $totalPrice / $totalProducts : 0;
            $brandData[] = [
                'brand_name' => $item->name,
                'total_products' => $totalProducts,
                'total_price' => $totalPrice,
                'average_price' => round($averagePrice, 2),
            ];
        }
        return response()->json($brandData);
    }
}
