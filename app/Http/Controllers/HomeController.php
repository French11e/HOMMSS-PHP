<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        return view('index');
    }

    /**
     * Search for products
     */
    public function search(Request $request)
    {
        $query = $request->input('query');
        
        // Validate search query
        if (strlen($query) < 3) {
            return response()->json([]);
        }
        
        // Search for products with category information
        $products = Product::with('category')
            ->where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')
            ->orWhere('short_description', 'like', '%' . $query . '%')
            ->orWhereHas('category', function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->orWhereHas('brand', function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%');
            })
            ->select('id', 'name', 'slug', 'image', 'regular_price', 'category_id')
            ->limit(10)
            ->get();
        
        return response()->json($products);
    }
}


