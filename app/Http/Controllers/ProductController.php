<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Handle cart count for authenticated user
        $cartCount = 0;
        if (Auth::check()) {
            $userId = Auth::id();
            $cartCount = Cart::where('user_id', $userId)->count();
            session(['cart_count' => $cartCount]);
        }

        // Start with base query
        $query = Product::query();

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('product_name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('product_name', 'desc');
                    break;
                default:
                    $query->latest(); // newest by default
                    break;
            }
        } else {
            $query->latest(); // Default sorting
        }

        // Get categories for sidebar
        $categories = Product::select('category')->distinct()->pluck('category')->toArray();

        // Paginate the results
        $products = $query->paginate(12);

        // Get the min and max price ranges from actual products
        $priceRange = [
            'min' => Product::min('price') ?: 0,
            'max' => Product::max('price') ?: 10000000
        ];

        return view('layouts.product', compact('products', 'cartCount', 'categories', 'priceRange'));
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            // If query is empty, show all products
            $products = Product::latest()->paginate(12);
        } else {
            // If there's a query, search for matching products
            $products = Product::where('product_name', 'LIKE', "%{$query}%")
                ->orWhere('category', 'LIKE', "%{$query}%")
                ->latest()
                ->paginate(12);
        }

        if ($request->ajax()) {
            return view('partials.product-list', compact('products'));
        }

        // Get the min and max price ranges from actual products
        $priceRange = [
            'min' => Product::min('price') ?: 0,
            'max' => Product::max('price') ?: 10000000
        ];

        // Get categories for sidebar
        $categories = Product::select('category')->distinct()->pluck('category')->toArray();
        $cartCount = session('cart_count', 0);

        return view('layouts.product', compact('products', 'cartCount', 'categories', 'priceRange'));
    }

    // Add a combined filter method that handles filter, sorting, and search
    public function filter(Request $request)
    {
        // Handle cart count for authenticated user
        $cartCount = 0;
        if (Auth::check()) {
            $userId = Auth::id();
            $cartCount = Cart::where('user_id', $userId)->count();
            session(['cart_count' => $cartCount]);
        }

        // Start with base query
        $query = Product::query();

        // Apply search if provided
        if ($request->has('query') && !empty($request->query)) {
            $searchQuery = $request->query;
            $query->where(function($q) use ($searchQuery) {
                $q->where('product_name', 'LIKE', "%{$searchQuery}%")
                  ->orWhere('category', 'LIKE', "%{$searchQuery}%");
            });
        }

        // Filter by category
        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        // Filter by price range
        if ($request->has('min_price') && is_numeric($request->min_price)) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price') && is_numeric($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort products
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price-low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('product_name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('product_name', 'desc');
                    break;
                default:
                    $query->latest(); // newest by default
                    break;
            }
        } else {
            $query->latest(); // Default sorting
        }

        // Paginate the results
        $products = $query->paginate(12);

        if ($request->ajax()) {
            return view('partials.product-list', compact('products'));
        }

        // Get categories for sidebar
        $categories = Product::select('category')->distinct()->pluck('category')->toArray();

        // Get the min and max price ranges from actual products
        $priceRange = [
            'min' => Product::min('price') ?: 0,
            'max' => Product::max('price') ?: 10000000
        ];

        return view('layouts.product', compact('products', 'cartCount', 'categories', 'priceRange'));
    }
}