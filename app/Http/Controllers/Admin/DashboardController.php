<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductPackage;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Landmark;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Build last 14 days series
        $days = collect(range(0, 13))->map(fn($i) => Carbon::today()->subDays(13 - $i));
        
        $ordersByDay = $days->mapWithKeys(function ($day) {
            $count = Order::whereDate('order_date', $day)->count();
            return [$day->format('Y-m-d') => $count];
        });
        
        $salesByDay = $days->mapWithKeys(function ($day) {
            $sum = Order::whereDate('order_date', $day)->sum('total');
            return [$day->format('Y-m-d') => (float)$sum];
        });

        // Payment split
        $paymentSplit = [
            'paypal' => Order::where('payment_method', 'paypal')->count(),
        ];

        // Status split
        $statusSplit = [
            'processing' => Order::where('order_status', 'processing')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        // Top countries
        $topCountries = Order::selectRaw('country, COUNT(*) as cnt')
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('cnt')
            ->limit(5)
            ->get();

        // Recent orders
        $recent = Order::latest('order_date')->limit(8)->get();

        // Package statistics
        $totalPackages = ProductPackage::count();
        $activePackages = ProductPackage::where('is_active', true)->count();
        $packageProducts = Product::where('is_package', true)->count();
        $recentPackages = ProductPackage::with('product')->latest()->limit(5)->get();

        // Content statistics
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalCities = City::count();
        $totalCountries = Country::count();
        $totalLandmarks = Landmark::count();
        $totalUsers = User::count();

        // Revenue metrics
        $totalRevenue = Order::sum('total');
        $monthlyRevenue = Order::whereMonth('order_date', now()->month)
            ->whereYear('order_date', now()->year)
            ->sum('total');
        $todayRevenue = Order::whereDate('order_date', today())->sum('total');
        $weeklyRevenue = Order::whereBetween('order_date', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total');

        // Order metrics
        $totalOrders = Order::count();
        $processingOrders = Order::where('order_status', 'processing')->count();
        $deliveredOrders = Order::where('order_status', 'delivered')->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();

        // Recent activities
        $recentActivities = collect([
            ['type' => 'order', 'count' => Order::whereDate('created_at', today())->count()],
            ['type' => 'product', 'count' => Product::whereDate('created_at', today())->count()],
            ['type' => 'landmark', 'count' => Landmark::whereDate('created_at', today())->count()],
        ]);

        return view('admin.dashboard', compact(
            'days',
            'salesByDay',
            'ordersByDay',
            'paymentSplit',
            'statusSplit',
            'topCountries',
            'recent',
            'totalPackages',
            'activePackages',
            'packageProducts',
            'recentPackages',
            'totalProducts',
            'totalCategories',
            'totalCities',
            'totalCountries',
            'totalLandmarks',
            'totalUsers',
            'totalRevenue',
            'monthlyRevenue',
            'todayRevenue',
            'weeklyRevenue',
            'totalOrders',
            'processingOrders',
            'deliveredOrders',
            'cancelledOrders',
            'recentActivities'
        ));
    }
}
