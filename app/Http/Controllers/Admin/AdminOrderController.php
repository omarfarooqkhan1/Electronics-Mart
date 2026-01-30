<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get status counts for filter tabs
        $statusCounts = [
            'all' => Order::count(),
            'processing' => Order::where('order_status', 'processing')->count(),
            'confirmed' => Order::where('order_status', 'confirmed')->count(),
            'shipped' => Order::where('order_status', 'shipped')->count(),
            'delivered' => Order::where('order_status', 'delivered')->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];
        
        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }
    
    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.show', compact('order'));
    }
    
    public function edit(Order $order)
    {
        $order->load(['user', 'orderItems.product']);
        return view('admin.orders.edit', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status' => 'required|in:processing,confirmed,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,completed,failed',
            'notes' => 'nullable|string',
            'shipping_address' => 'nullable|array',
            'shipping_address.street' => 'nullable|string',
            'shipping_address.city' => 'nullable|string',
            'shipping_address.state' => 'nullable|string',
            'shipping_address.postal_code' => 'nullable|string',
            'shipping_address.country' => 'nullable|string',
        ]);
        
        $order->update($validated);
        
        return redirect()->route('admin.orders.show', $order)
                        ->with('success', 'Order updated successfully.');
    }
    
    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:processing,confirmed,shipped,delivered,cancelled'
        ]);
        
        $order->update(['order_status' => $validated['status']]);
        
        return redirect()->back()->with('success', 'Order status updated successfully.');
    }
    
    public function destroy(Order $order)
    {
        // Only allow deletion of cancelled orders
        if ($order->order_status !== 'cancelled') {
            return redirect()->route('admin.orders.index')
                            ->with('error', 'Only cancelled orders can be deleted.');
        }
        
        $order->delete();
        
        return redirect()->route('admin.orders.index')
                        ->with('success', 'Order deleted successfully.');
    }
}