<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{


    public function search(Request $request)
    {
        $query = trim($request->input('q'));

        $request->validate([
            'q' => 'required|string|max:255',
        ]);
        // Build product query and paginate
        $productsQuery = Product::query();
        if ($query !== '') {
            // Search product columns and related category/brand names via relations
            $productsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%")
                    ->orWhere('price', 'like', "%{$query}%")
                    ->orWhere('brand', 'like', "%{$query}%");
            })
            ->orWhereHas('category', function ($cq) use ($query) {
                $cq->where('name', 'like', "%{$query}%");
            })
            ->orWhereHas('brand', function ($bq) use ($query) {
                $bq->where('name', 'like', "%{$query}%");
            });
        }

        $isAjax = $request->ajax();
        if ($isAjax) {
            $products = $productsQuery->orderBy('name', 'asc')->take(10)->get();
        } else {
            $products = $productsQuery->orderBy('name', 'asc')->paginate(13)->appends(['q' => $query]);
        }

        // Categories
        $categoriesQuery = Category::query();
        if ($query !== '') {
            $categoriesQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }
        if ($isAjax) {
            $categories = $categoriesQuery->orderBy('name', 'asc')->take(6)->get();
        } else {
            $categories = $categoriesQuery->orderBy('name', 'asc')->paginate(13)->appends(['q' => $query]);
        }

        // Brands
        $brandsQuery = Brand::query();
        if ($query !== '') {
            $brandsQuery->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            });
        }
        if ($isAjax) {
            $brands = $brandsQuery->orderBy('name', 'asc')->take(8)->get();
        } else {
            $brands = $brandsQuery->orderBy('name', 'asc')->paginate(13)->appends(['q' => $query]);
        }

        // Return JSON HTML for AJAX, otherwise full view
        if ($request->ajax()) {
            $view = view('search.partials.results', compact('products', 'brands', 'categories', 'query'))->render();
            return response()->json(['html' => $view]);
        }

        return view('search.results', compact('products', 'brands', 'categories', 'query'));
    }
}
