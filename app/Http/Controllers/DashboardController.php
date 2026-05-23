<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Notification;

class DashboardController extends Controller
{
    /**
     * Display the dashboard based on user role.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $data = [];

        // Common data for all roles
        $data['user'] = $user;
        $data['unreadNotifications'] = Notification::where('user_id', $user->id)->orWhereNull('user_id')
            ->latest()
            ->take(10)
            ->get();

        // Role-specific data
        switch ($user->role) {
            case 'admin':
                $data['totalProducts'] = Product::count();
                $data['totalSales'] = Sale::count();
                $data['todaySales'] = Sale::whereDate('created_at', today())->count();
                $data['lowStockProducts'] = Product::whereRaw('quantity <= min_stock_level')->count();
                $data['recentSales'] = Sale::with('user')->latest()->take(5)->get();
                break;

            case 'cashier':
                $data['todaySales'] = Sale::where('user_id', $user->id)->whereDate('created_at', today())->count();
                $data['recentSales'] = Sale::where('user_id', $user->id)->latest()->take(5)->get();
                $data['activeProducts'] = Product::where('quantity', '>', 0)->where('is_active', true)->count();
                break;

            case 'inventory_staff':
                $data['totalProducts'] = Product::count();
                $data['lowStockProducts'] = Product::whereRaw('quantity <= min_stock_level')->get();
                $data['outOfStockProducts'] = Product::where('quantity', '<=', 0)->get();
                $data['recentMovements'] = \App\Models\InventoryMovement::with(['product', 'user'])
                    ->latest()
                    ->take(5)
                    ->get();
                break;
        }

        return view('dashboard', $data);
    }
}
