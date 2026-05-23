@extends('layouts.app')

@section('header', 'Inventory Reports')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventory Reports</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Inventory
        </a>
    </div>
</div>

<!-- Report Period -->
<div class="card mb-4">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    {{ $periodLabel }}
                </h5>
                <p class="text-muted mb-0">Inventory movements and trends from {{ $startDate->format('M d, Y') }} to {{ now()->format('M d, Y') }}</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fas fa-print me-1"></i> Print Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Period Selection -->
<div class="card mb-4">
    <div class="card-body">
        <h5 class="mb-3">
            <i class="fas fa-filter me-2"></i>
            Select Report Period
        </h5>
        <div class="row">
            <div class="col-md-2 mb-2">
                <a href="{{ route('inventory.reports', ['period' => 'today']) }}" 
                   class="btn {{ $period === 'today' ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                    <i class="fas fa-calendar-day me-1"></i> Today
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="{{ route('inventory.reports', ['period' => '7days']) }}" 
                   class="btn {{ $period === '7days' ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                    <i class="fas fa-calendar-week me-1"></i> 7 Days
                </a>
            </div>
            <div class="col-md-2 mb-2">
                <a href="{{ route('inventory.reports', ['period' => '30days']) }}" 
                   class="btn {{ $period === '30days' ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                    <i class="fas fa-calendar me-1"></i> 30 Days
                </a>
            </div>
            <div class="col-md-3 mb-2">
                <a href="{{ route('inventory.reports', ['period' => 'weekly']) }}" 
                   class="btn {{ $period === 'weekly' ? 'btn-primary' : 'btn-outline-primary' }} w-100">
                    <i class="fas fa-calendar-alt me-1"></i> 4 Weeks
                </a>
            </div>
            <div class="col-md-3 mb-2">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-calendar"></i>
                    </span>
                    <input type="date" id="customDate" class="form-control" 
                           value="{{ $startDate->format('Y-m-d') }}" 
                           onchange="loadCustomReport(this.value)">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Movement Chart -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-chart-line me-2"></i>
            {{ $period === 'weekly' ? 'Weekly' : 'Daily' }} Movement Trends
        </h5>
    </div>
    <div class="card-body">
        @if($period === 'weekly' && count($weeklyData) > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Week</th>
                            <th class="text-end">Stock In</th>
                            <th class="text-end">Stock Out</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Adjustments</th>
                            <th class="text-end">Net Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($weeklyData as $week => $data)
                            <tr>
                                <td>Week {{ $week }}</td>
                                <td class="text-end text-success">{{ $data['stock_in'] }}</td>
                                <td class="text-end text-danger">{{ $data['stock_out'] }}</td>
                                <td class="text-end text-primary">{{ $data['sale'] }}</td>
                                <td class="text-end text-warning">{{ $data['adjustment'] }}</td>
                                <td class="text-end fw-bold">
                                    {{ $data['stock_in'] - $data['stock_out'] - $data['sale'] + $data['adjustment'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @elseif(count($chartData) > 0)
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th class="text-end">Stock In</th>
                            <th class="text-end">Stock Out</th>
                            <th class="text-end">Sales</th>
                            <th class="text-end">Adjustments</th>
                            <th class="text-end">Net Change</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($chartData as $data)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($data['date'])->format('M d, Y') }}</td>
                                <td class="text-end text-success">{{ $data['stock_in'] }}</td>
                                <td class="text-end text-danger">{{ $data['stock_out'] }}</td>
                                <td class="text-end text-primary">{{ $data['sale'] }}</td>
                                <td class="text-end text-warning">{{ $data['adjustment'] }}</td>
                                <td class="text-end fw-bold">
                                    {{ $data['stock_in'] - $data['stock_out'] - $data['sale'] + $data['adjustment'] }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <p class="text-muted">No movement data available for the selected period.</p>
            </div>
        @endif
    </div>
</div>

<div class="row">
    <!-- Top Products -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-trophy me-2"></i>
                    Top 10 Most Active Products
                </h5>
            </div>
            <div class="card-body">
                @if($topProducts->count() > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th class="text-end">Total Movement</th>
                                    <th class="text-end">Current Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($topProducts as $index => $topProduct)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary me-2">{{ $index + 1 }}</span>
                                            <strong>{{ $topProduct->product->name }}</strong>
                                        </td>
                                        <td>{{ $topProduct->product->sku }}</td>
                                        <td class="text-end fw-bold">{{ $topProduct->total_movement }}</td>
                                        <td class="text-end">
                                            <span class="badge 
                                                @if($topProduct->product->quantity == 0) bg-danger
                                                @elseif($topProduct->product->quantity <= $topProduct->product->min_stock_level) bg-warning
                                                @else bg-success @endif">
                                                {{ $topProduct->product->quantity }} {{ $topProduct->product->unit }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No product activity recorded in the last 30 days.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-pie me-2"></i>
                    Summary Statistics
                </h5>
            </div>
            <div class="card-body">
                @php
                    if ($period === 'weekly' && isset($weeklyData)) {
                        $totalStockIn = array_sum(array_column($weeklyData, 'stock_in'));
                        $totalStockOut = array_sum(array_column($weeklyData, 'stock_out'));
                        $totalSales = array_sum(array_column($weeklyData, 'stock_out')); // Sales are now stock_out
                        $totalAdjustments = array_sum(array_column($weeklyData, 'adjustment'));
                        $netChange = $totalStockIn - $totalStockOut + $totalAdjustments;
                    } else {
                        $totalStockIn = array_sum(array_column($chartData, 'stock_in'));
                        $totalStockOut = array_sum(array_column($chartData, 'stock_out'));
                        $totalSales = array_sum(array_column($chartData, 'stock_out')); // Sales are now stock_out
                        $totalAdjustments = array_sum(array_column($chartData, 'adjustment'));
                        $netChange = $totalStockIn - $totalStockOut + $totalAdjustments;
                    }
                @endphp
                
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Stock In:</span>
                        <span class="text-success fw-bold">{{ $totalStockIn }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Stock Out:</span>
                        <span class="text-danger fw-bold">{{ $totalStockOut }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Sales:</span>
                        <span class="text-primary fw-bold">{{ $totalSales }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Adjustments:</span>
                        <span class="text-warning fw-bold">{{ $totalAdjustments }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-2">
                        <strong>Total Income:</strong>
                        <strong class="text-success">₱{{ number_format($totalIncome, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <strong>Net Change:</strong>
                        <strong class="
                            @if($netChange > 0) text-success
                            @elseif($netChange < 0) text-danger
                            @else text-muted @endif">
                            {{ $netChange > 0 ? '+' : '' }}{{ $netChange }}
                        </strong>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        <strong>Net Change</strong> represents the overall inventory change over the last 30 days. 
                        Positive means stock increased, negative means stock decreased.
                    </small>
                </div>

                <div class="text-center">
                    <a href="{{ route('inventory.low-stock') }}" class="btn btn-warning btn-sm me-2">
                        <i class="fas fa-exclamation-triangle me-1"></i> Low Stock
                    </a>
                    <a href="{{ route('inventory.out-of-stock') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-times-circle me-1"></i> Out of Stock
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function loadCustomReport(date) {
    if (date) {
        window.location.href = '{{ route('inventory.reports') }}?period=custom&date=' + date;
    }
}

// Auto-refresh page every 5 minutes for real-time data
setInterval(function() {
    const currentUrl = new URL(window.location.href);
    const params = currentUrl.searchParams;
    window.location.reload();
}, 300000);
</script>
@endpush
