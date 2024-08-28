<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PurchaseOrder;
use Carbon\Carbon;

class DashboardController extends Controller
{

      /**
     * Show the admin dashboard with purchase orders information.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Fetch all purchase orders for the current month with eager loading of products and client
        $allOrders = PurchaseOrder::whereBetween('order_creation_date', [$startOfMonth, $endOfMonth])
            ->with('products', 'client')
            ->get();

        // Separate the orders by status
        $completedOrders = $allOrders->where('status', 'completed');
        $pendingOrders = $allOrders->where('status', 'pending');
        $processingOrders = $allOrders->where('status', 'processing');

        // Calculate total prices for all orders
        $totalPriceAllMonth = $allOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        $totalPriceAllDollars = $allOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity / (float) $order->trm;
            });
        }, 0);

        // Calculate total prices for completed orders
        $totalPriceCompletedMonth = $completedOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        $totalPriceCompletedDollars = $completedOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity / (float) $order->trm;
            });
        }, 0);

        // Calculate total prices for pending orders
        $totalPricePendingMonth = $pendingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        $totalPricePendingDollars = $pendingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity / (float) $order->trm;
            });
        }, 0);

        // Calculate total prices for processing orders
        $totalPriceProcessingMonth = $processingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        $totalPriceProcessingDollars = $processingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity / (float) $order->trm;
            });
        }, 0);

        // Fetch the last 10 orders
        $latestOrders = PurchaseOrder::orderBy('created_at', 'desc')
            ->take(10)
            ->with('client')
            ->get();

        return view('admin.dashboard', compact(
            'allOrders',
            'completedOrders',
            'pendingOrders',
            'processingOrders',
            'totalPriceAllMonth',
            'totalPriceAllDollars',
            'totalPriceCompletedMonth',
            'totalPriceCompletedDollars',
            'totalPricePendingMonth',
            'totalPricePendingDollars',
            'totalPriceProcessingMonth',
            'totalPriceProcessingDollars',
            'latestOrders'
        ));
    }
    

}
