<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $categories = Category::orderBy('created_at', 'desc')->paginate(10);
            return view('categories.index', compact('categories'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching categories.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('categories.create');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while loading the create category form.');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CategorieRequest $request)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('main_image')) {
                $img_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
                $path = $request->file('main_image')->store('categories', 'public');
                $validated['main_image'] = $path;
            }
            Category::create($validated);
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while creating the category.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $category = Category::findOrFail($category->id);
            return view('categories.show', compact('category'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while fetching the category details.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategorieRequest $request, Category $category)
    {
        try {
            $validated = $request->validated();
            if ($request->hasFile('main_image')) {
                if ($category->main_image && Storage::disk('public')->exists($category->main_image)) {
                    Storage::disk('public')->delete($category->main_image);
                }
                $img_name = time() . '_' . $request->file('main_image')->getClientOriginalName();
                $path = $request->file('main_image')->store('categories', 'public');
                $validated['main_image'] = $path;
            }
            $category->update($validated);
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'An error occurred while updating the category.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category = Category::findOrFail($category->id);
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {


            return redirect()->back()->with('error', 'An error occurred while deleting the category.');
        }
    }
}
