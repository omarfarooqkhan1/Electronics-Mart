<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $dateFilter = $request->get('date_filter', '30'); // default last 30 days
        $startDate = Carbon::now()->subDays((int)$dateFilter);

        // Basic counts
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('is_active', true)->count(),
            'total_categories' => Category::count(),
            'active_categories' => Category::where('is_active', true)->count(),
            'total_customers' => User::where('role', 'customer')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
        ];

        // Time-based statistics
        $stats['new_orders'] = Order::where('created_at', '>=', $startDate)->count();
        $stats['new_registrations'] = User::where('created_at', '>=', $startDate)->count();
        $stats['pending_orders'] = Order::where('status', 'processing')->count();
        $stats['shipped_orders'] = Order::where('status', 'shipped')->count();
        $stats['completed_orders'] = Order::whereIn('status', ['delivered', 'confirmed'])->count();
        
        // Revenue statistics
        $stats['recent_revenue'] = Order::whereIn('status', ['delivered', 'confirmed'])
            ->where('created_at', '>=', $startDate)
            ->sum('total');
        
        $stats['total_revenue'] = Order::whereIn('status', ['delivered', 'confirmed'])->sum('total');

        // Payment method statistics
        $stats['card_payments'] = Order::where('payment_method', 'card')
            ->where('created_at', '>=', $startDate)
            ->count();
        
        $stats['paypal_payments'] = Order::where('payment_method', 'paypal')
            ->where('created_at', '>=', $startDate)
            ->count();
            
        $stats['bank_transfer_payments'] = Order::where('payment_method', 'bank_transfer')
            ->where('created_at', '>=', $startDate)
            ->count();

        // Low stock products (simplified - no variants)
        $lowStockProducts = Product::where('is_active', true)
            ->where('stock_quantity', '<=', 10) // Consider products with 10 or less as low stock
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->limit(10)
            ->get();

        // Recent orders
        $recentOrders = Order::with(['user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Recent registrations
        $recentRegistrations = User::where('role', 'customer')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Top selling products (last 30 days)
        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('orders.created_at', '>=', $startDate)
            ->whereIn('orders.status', ['delivered', 'confirmed'])
            ->select('products.name', 'products.id', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats', 
            'lowStockProducts', 
            'recentOrders', 
            'recentRegistrations', 
            'topProducts',
            'dateFilter'
        ));
    }
}