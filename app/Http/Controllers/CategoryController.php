<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategorieRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {

            $query = Category::orderBy('created_at', 'desc');
            $user = Auth::user();
            if ($user && ! $user->isAdmin()) {
                $query->where('user_id', $user->id);
            }
            $categories = $query->paginate(10);
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
            $this->authorize('create', Category::class);
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
            $this->authorize('create', Category::class);
            $validated = $request->validated();
            $user = Auth::user();
            if ($user) {
                $validated['user_id'] = $user->id;
            }
         
             if ($request->hasFile('image') || $request->hasFile('main_image')) {
                // accept either 'image' or legacy 'main_image' from forms
                $fileKey = $request->hasFile('image') ? 'image' : 'main_image';
                $path = $request->file($fileKey)->store('categories', 'public');
                // save to DB column 'image'
                $validated['image'] = $path;
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
            $this->authorize('view', $category);
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
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CategorieRequest $request, Category $category)
    {
        try {
            $this->authorize('update', $category);
            $validated = $request->validated();
                  if ($request->hasFile('image') || $request->hasFile('main_image')) {
                $fileKey = $request->hasFile('image') ? 'image' : 'main_image';
                // delete old image if exists in the 'image' column
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                $path = $request->file($fileKey)->store('categories', 'public');
                
                $validated['image'] = $path;
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
            $this->authorize('delete', $category);
            $category->delete();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {


            return redirect()->back()->with('error', 'An error occurred while deleting the category.');
        }
    }
}
