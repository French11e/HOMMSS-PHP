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
        
        // Validate search input
        $validated = $request->validate([
            'query' => 'required|string|min:3|max:100',
        ]);
        
        // Use parameter binding for search
        $results = Product::where('name', 'LIKE', "%{$validated['query']}%")
                         ->orWhere('description', 'LIKE', "%{$validated['query']}%")
                         ->limit(10)
                         ->get();
        
        return response()->json($results);
    }
}






