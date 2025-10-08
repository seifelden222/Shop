<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FavoriteController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }
        
        $favorite = Favorite::with('product')
            ->where('user_id', Auth::user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(12);
        return view('favorites.index', compact('favorite'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $productId = $request->get('product_id');
        $userId = Auth::user()->id;
        
        $favorite = Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();
            
        if ($favorite) {
            return redirect()->back()->with('info', 'Product is already in your favorites.');
        }
        
        return DB::transaction(function () use ($userId, $productId) {
            Favorite::create([
                'user_id' => $userId,
                'product_id' => $productId,
                'is_favorite' => true,
            ]);
            return redirect()->back()->with('success', 'Product added to your favorites.');
        });
    }


    public function destroy($favoriteId=null) {
        $userId = Auth::user()->id;
        if($favoriteId =="all"){
            Favorite::where('user_id',$userId)->delete();
            return redirect()->back()->with('success', 'All favorites removed successfully.');
        }
        $favorite =Favorite::where('user_id', $userId)
            ->where('id', $favoriteId)
            ->first();
        if (!$favorite) 
            return redirect()->back()->with('error', 'Favorite not found.');

        $favorite->delete();
        if(Favorite::count() <= 0)
            return redirect()->route('welcome')->with('success', 'Favorite removed successfully.');
        
        return redirect()->route('favorites.index')->with('success', 'Favorite removed successfully.');

    }
}
