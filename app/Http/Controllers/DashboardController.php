<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->is_admin == 1) {
            $orders = Order::latest()->paginate(10);
            $users = User::paginate(10, ['*'], 'user_page');
            $totalProduct = Product::count();
            $totalUser = User::where('is_admin', 0)->count();
            $totalDiscount = Discount::count();
            $totalTransaction = Order::where('payment_status' , 'paid')->count();
            $totalPaidCost = Order::where('payment_status', 'paid')->sum('total_cost');

            $monthlygrowth = $this->getMonthlyGrowth();
            $salesChartData = $this->getSalesChartData();
            $topProductsData = $this->getTopProductsData();
            $recentOrders = $this->getRecentOrders();

            return view('admin.dashboard', compact('user', 'users', 'orders', 'totalProduct', 'totalUser', 'totalDiscount', 'totalTransaction', 'totalPaidCost', 'salesChartData','topProductsData','recentOrders', 'monthlygrowth'));
        }

        return view('home', compact('user'));
    }
    public function customers()
    {
        $user = Auth::user();

        if ($user->is_admin == 1) {
            $users = User::paginate(10, ['*']);
        } else {
            return view('home', compact('user'));
        }

        return view('admin.user', compact('users'));
    }

    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->withErrors(['error' => 'Anda belum login!']);
        }

        Auth::logout();
        session()->flush();

        return redirect()->route('login')->with('success', 'Logout berhasil!');
    }

    public function orders()
    {
        $user = Auth::user();

        if ($user->is_admin == 1) {
            $orders = Order::latest()->paginate(15);
        } else {
            return view('home', compact('user'));
        }

        return view('admin.transactions', compact('user', 'orders'));
    }

    public function showOrder($id)
    {
        $order = Order::findOrFail($id);
        return view('dashboard.order-detail', compact('order'));
    }

    public function products()
    {
        $user = Auth::user();

        if ($user->is_admin == 1) {
            $products = Product::latest()->paginate(10);
        } else {
            return view('home', compact('user'));
        }

        return view('admin.products', compact('user', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|integer|min:0',
            'category' => 'required|string',
            'stock' => 'required|integer|min:0',
            'images' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('images')) {
            $imagePath = $request->file('images')->store('products', 'public');
        } else {
            dd('File image tidak diterima');
        }

        Product::create([
            'product_name' => $validated['product_name'],
            'price' => $validated['price'],
            'category' => $validated['category'],
            'stock' => $validated['stock'],
            'images' => $imagePath,
        ]);

        return redirect()->back()->with('success', 'Product added successfully.');
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail($id);
        return view('admin.edit-product', compact('product'));
    }

    public function updateProduct(Request $request, $id)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'nullable|string',
            'stock' => 'required|numeric|min:0',
            'images' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::findOrFail($id);

        if ($request->hasFile('images')) {
            if ($product->images) {
                $oldImagePath = str_replace('/storage', 'public', $product->images);
                Storage::delete($oldImagePath);
            }

            $path = $request->file('images')->store('products', 'public');
            $validated['images'] =  $path;
        } else {
            unset($validated['images']);
        }

        $product->update($validated);

        return redirect()->back()->with('success', 'Product updated successfully');
    }

    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products')->with('success', 'Product has been deleted successfully');
    }

    public function getSalesChartDataAjax(Request $request)
    {
        $days = $request->get('days', 7);
        return response()->json($this->getSalesChartData($days));
    }

    private function getSalesChartData($days = 7)
    {
        $endDate = Carbon::now();
        $startDate = $days == 1 
            ? Carbon::now()->startOfDay() 
            : Carbon::now()->subDays($days - 1)->startOfDay();

        if ($days == 1) {
            $salesData = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $startDate)
                ->select(
                    DB::raw('HOUR(created_at) as hour'),
                    DB::raw('SUM(total_cost) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('HOUR(created_at)'))
                ->orderBy('hour')
                ->get();

            $labels = [];
            $sales = [];
            $orders = [];

            for ($hour = 9; $hour <= 16; $hour++) {
                $labels[] = date('ga', strtotime("$hour:00"));
                $hourData = $salesData->firstWhere('hour', $hour);
                $sales[] = $hourData ? $hourData->total : 0;
                $orders[] = $hourData ? $hourData->count : 0;
            }
        } else {
            $salesData = Order::where('payment_status', 'paid')
                ->where('created_at', '>=', $startDate)
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_cost) as total'),
                    DB::raw('COUNT(*) as count')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('date')
                ->get();

            $labels = [];
            $sales = [];
            $orders = [];

            for ($i = 0; $i < $days; $i++) {
                $date = $startDate->copy()->addDays($i);
                $formattedDate = $date->format('j M');
                $labels[] = $formattedDate;

                $dateStr = $date->format('Y-m-d');
                $dayData = $salesData->firstWhere('date', $dateStr);
                $sales[] = $dayData ? $dayData->total : 0;
                $orders[] = $dayData ? $dayData->count : 0;
            }
        }

        return [
            'labels' => $labels,
            'sales' => $sales,
            'orders' => $orders
        ];
    }

    private function getTopProductsData()
    {
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.payment_status', 'paid')
            ->where('orders.created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                'category as category',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue')
            )
            ->groupBy('category')
            ->orderByDesc('revenue')
            ->limit(5)
            ->get();

        $labels = $topProducts->pluck('category')->toArray();
        $data = $topProducts->pluck('revenue')->toArray();

        if (count($labels) < 5) {
            $labels[] = 'Others';
            $data[] = rand(5, 10);
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    private function getRecentOrders($limit = 4)
    {
        return Order::with('user')
            ->select('order_id', 'user_id', 'created_at', 'total_cost', 'payment_status')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function getSalesPerformance(Request $request)
    {
        $days = $request->get('days', 7);
        return response()->json($this->getSalesChartData($days));
    }

    private function getMonthlyGrowth()
    {
        $currentMonth = Carbon::now();
        $previousMonth = Carbon::now()->subMonth();

        $currentMonthSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$currentMonth->copy()->startOfMonth(), $currentMonth->copy()->endOfMonth()])
            ->sum('total_cost');

        $previousMonthSales = Order::where('payment_status', 'paid')
            ->whereBetween('created_at', [$previousMonth->copy()->startOfMonth(), $previousMonth->copy()->endOfMonth()])
            ->sum('total_cost');

        $salesGrowth = $previousMonthSales > 0 
            ? round((($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100, 1)
            : 0;

        $newProducts = Product::whereBetween('created_at', [$currentMonth->copy()->startOfMonth(), $currentMonth->copy()->endOfMonth()])
            ->count();

        $currentMonthCustomers = User::where('is_admin', 0)
            ->whereBetween('created_at', [$currentMonth->copy()->startOfMonth(), $currentMonth->copy()->endOfMonth()])
            ->count();

        $previousMonthCustomers = User::where('is_admin', 0)
            ->whereBetween('created_at', [$previousMonth->copy()->startOfMonth(), $previousMonth->copy()->endOfMonth()])
            ->count();

        $customerGrowth = $previousMonthCustomers > 0 
            ? round((($currentMonthCustomers - $previousMonthCustomers) / $previousMonthCustomers) * 100, 1)
            : 0;

        $expiredDiscounts = Discount::whereBetween('end_date', [Carbon::now()->subWeek(), Carbon::now()])
            ->count();

        return [
            'salesGrowth' => $salesGrowth,
            'newProducts' => $newProducts,
            'customerGrowth' => $customerGrowth,
            'expiredDiscounts' => $expiredDiscounts
        ];
    }
    public function showAjax($id)
{
    $order = Order::with('items')->findOrFail($id);

    $subtotal = $order->items->sum('total_price');

    return response()->json([
        'order_id' => $order->order_id,
        'tanggal' => $order->created_at->format('d M Y'),
        'status' => $order->payment_status,
        'total' => number_format($order->total_cost, 0, ',', '.'),

        // Info Customer & Pengiriman
        'name' => $order->name,
        'email' => $order->email,
        'phone' => $order->phone,
        'address' => $order->address,
        'province' => $order->province,
        'city' => $order->city,
        'courier' => $order->courier,
        'service' => $order->service,
        'shipping_amount' => number_format($order->shipping_amount, 0, ',', '.'),
        'discount_amount' => number_format($order->discount_amount, 0, ',', '.'),
        'subtotal' => number_format($subtotal, 0, ',', '.'),

        // Items
        'items' => $order->items->map(function($item) {
            return [
                'product_name' => $item->product_name,
                'quantity' => $item->quantity,
                'total_price' => number_format($item->total_price, 0, ',', '.'),
            ];
        }),
    ]);
}
public function storeUser(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'is_admin' => 'required|boolean',
        'email_verified' => 'required|boolean',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password), // Hash password
        'is_admin' => $request->is_admin,
        'email_verified' => $request->email_verified,
    ]);

    return back()->with('success', 'User berhasil ditambahkan.');
}
public function destroyUser($id)
{
    $user = User::findOrFail($id);
    $user->delete();

    return back()->with('success', 'User berhasil dihapus.');
}




}
