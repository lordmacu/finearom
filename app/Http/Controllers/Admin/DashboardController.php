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
        if (!auth()->user()->hasRole('Gerente')) {
            return redirect()->route('admin.purchase_orders.index');
        }

        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $query = PurchaseOrder::query();
        
        // Check for filter input
        $filterType = request('filter_type');
        $sortDirection = request('sort_direction', 'desc'); // Default to descending
        
        $creationDate = request('creation_date');
        
        // Inicializamos las fechas por defecto (todo el mes)
        $fromDate = $startOfMonth;
        $toDate = $endOfMonth;
        
        if ($creationDate) {
            // Separar las fechas "from" y "to"
            [$from, $to] = explode(' - ', $creationDate);
        
            // Crear instancias de Carbon para ambas fechas
            $fromDate = Carbon::createFromFormat('Y-m-d', $from)->startOfDay(); // Desde el inicio del día
            $toDate = Carbon::createFromFormat('Y-m-d', $to)->endOfDay(); // Hasta el final del día
        
            // Si la fecha "from" y "to" son iguales, ajusta el tiempo en "from" y "to"
            if ($fromDate->equalTo($toDate)) {
                $fromDate = $fromDate->startOfDay(); // Comienza al inicio del día
                $toDate = $toDate->endOfDay(); // Termina al final del mismo día
            }
            $query->whereBetween('order_creation_date', [$fromDate, $toDate]);
        }
        
        

        if ($filterType === 'date') {

            $query->orderBy('created_at', $sortDirection);
        } elseif ($filterType === 'total') {
            $query->withSum('products as total_price', \DB::raw('price * quantity'))
                  ->orderBy('total_price', $sortDirection);
        }

        $allOrders = $query
        ->with('products', 'client')
        ->get();

        // Separate the orders by status
        $completedOrders = $allOrders->where('status', 'completed');
        $pendingOrders = $allOrders->where('status', 'pending');
        $processingOrders = $allOrders->where('status', 'processing');

        // Calculate total prices for all orders
        $totalPriceAllMonth = $allOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity * (float) $order->trm;
            });
        }, 0);

        $totalPriceAllDollars = $allOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        // Calculate total prices for completed orders
        $totalPriceCompletedMonth = $completedOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity * (float) $order->trm;
            });
        }, 0);

        $totalPriceCompletedDollars = $completedOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        // Calculate total prices for pending orders
        $totalPricePendingMonth = $pendingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity * (float) $order->trm;
            });
        }, 0);

        $totalPricePendingDollars = $pendingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        // Calculate total prices for processing orders
        $totalPriceProcessingMonth = $processingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity * (float) $order->trm;
            });
        }, 0);

        $totalPriceProcessingDollars = $processingOrders->reduce(function ($carry, $order) {
            return $carry + $order->products->sum(function ($product) use ($order) {
                return (float) $product->price * $product->pivot->quantity;
            });
        }, 0);

        // Fetch the last 10 orders
        if ($filterType === 'total') {
            $latestOrders = $query->with('products')
                ->withSum('products as total_price', \DB::raw('price * quantity'))
                ->orderBy('total_price', $sortDirection)
                ->take(10)
                ->with('client')
                ->get();
        } else {
            // Ordenar por fecha de creación

            $latestOrders = $query->orderBy('created_at', $sortDirection)
                ->take(10)
                ->with('client')
                ->get();
        }

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
            'latestOrders',
            'filterType',
            'sortDirection'
        ));
    }

 
}
