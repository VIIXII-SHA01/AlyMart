@extends('layouts.app')

@section('header', 'Dashboard')

@section('content')
<div class="row">
    <!-- Welcome Card -->
    <div class="col-12">
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Welcome back, {{ $user->name }}!</h5>
                <p class="card-text">
                    You are logged in as <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                    @if($user->role == 'admin')
                        - Full system access
                    @elseif($user->role == 'cashier')
                        - Sales and inventory access
                    @elseif($user->role == 'inventory_staff')
                        - Inventory management access
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Admin Dashboard -->
@if($user->isAdmin())
<div class="row">
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $totalProducts }}</h5>
                <p class="card-text">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $totalSales }}</h5>
                <p class="card-text">Total Sales</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">{{ $todaySales }}</h5>
                <p class="card-text">Today's Sales</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">{{ $lowStockProducts }}</h5>
                <p class="card-text">Low Stock Items</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Recent Sales</h6>
            </div>
            <div class="card-body scrollable-container" style="height: 400px; overflow-y: auto; overflow-x: auto; display: flex; flex-direction: column; padding: 0;">
                @if($recentSales->count() > 0)
                    <table class="table table-sm" style="margin-bottom: 0;">
                        <thead>
                            <tr>
                                <th>Transaction #</th>
                                <th>Cashier</th>
                                <th>Amount</th>
                                <th>Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentSales as $sale)
                            <tr>
                                <td>{{ $sale->transaction_number }}</td>
                                <td>{{ $sale->user->name }}</td>
                                <td>PHP {{ number_format($sale->total_amount, 2) }}</td>
                                <td>{{ $sale->created_at->format('H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-muted" style="padding: 1rem;">No recent sales found.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Notifications</h6>
            </div>
            <div class="card-body scrollable-container" style="height: 400px; overflow-y: auto; overflow-x: hidden;">
                @if($unreadNotifications->count() > 0)
                    @foreach($unreadNotifications as $notification)
                    <div class="alert alert-{{ $notification->type == 'low_stock' ? 'warning' : ($notification->type == 'critical' || $notification->type == 'out_of_stock' ? 'danger' : 'info') }} alert-sm">
                        <strong>{{ $notification->title }}</strong><br>
                        <small>{{ $notification->message }}</small>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No new notifications.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Cashier Dashboard -->
@if($user->isCashier())
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-success">{{ $todaySales }}</h5>
                <p class="card-text">Today's Sales</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-info">{{ $activeProducts }}</h5>
                <p class="card-text">Available Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">
                    <a href="{{ route('sales.index') }}" class="btn btn-primary">Start Sale</a>
                </h5>
                <p class="card-text">New Transaction</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6>Your Recent Sales</h6>
            </div>
            <div class="card-body">
                @if($recentSales->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Transaction #</th>
                                    <th>Amount</th>
                                    <th>Payment Method</th>
                                    <th>Time</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentSales as $sale)
                                <tr>
                                    <td>{{ $sale->transaction_number }}</td>
                                    <td>PHP {{ number_format($sale->total_amount, 2) }}</td>
                                    <td>{{ ucfirst($sale->payment_method) }}</td>
                                    <td>{{ $sale->created_at->format('M j, H:i') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No sales recorded today.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Inventory Staff Dashboard -->
@if($user->isInventoryStaff())
<div class="row">
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-primary">{{ $totalProducts }}</h5>
                <p class="card-text">Total Products</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-warning">{{ $lowStockProducts->count() }}</h5>
                <p class="card-text">Low Stock Items</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title text-danger">{{ $outOfStockProducts->count() }}</h5>
                <p class="card-text">Out of Stock</p>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Low Stock Products</h6>
            </div>
            <div class="card-body">
                @if($lowStockProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Current Stock</th>
                                    <th>Min Level</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <span class="badge bg-warning">{{ $product->quantity }}</span>
                                    </td>
                                    <td>{{ $product->min_stock_level }}</td>
                                    <td>
                                        <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-primary">Restock</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No products are low in stock.</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6>Recent Inventory Movements</h6>
            </div>
            <div class="card-body">
                @if($recentMovements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Type</th>
                                    <th>Quantity</th>
                                    <th>User</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->product->name }}</td>
                                    <td>
                                        <span class="badge bg-{{ $movement->movement_type == 'stock_in' ? 'success' : 'danger' }}">
                                            {{ ucfirst(str_replace('_', ' ', $movement->movement_type)) }}
                                        </span>
                                    </td>
                                    <td>{{ $movement->quantity }}</td>
                                    <td>{{ $movement->user->name }}</td>
                                    <td>{{ $movement->created_at->format('H:i') }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No recent inventory movements.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

@endsection
