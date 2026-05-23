@extends('layouts.app')

@section('header', 'Sale Details - ' . $sale->transaction_number)

@section('content')
<style>
    .table-scrollable {
        display: block !important;
        width: 100%;
        overflow-x: auto !important;
        overflow-y: auto !important;
        max-height: 500px !important;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        margin-bottom: 1rem;
    }
    
    .table-scrollable::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }
    
    .table-scrollable::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .table-scrollable::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .table-scrollable::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    .table-scrollable table {
        margin-bottom: 0 !important;
        width: 100%;
    }
    
    .table-scrollable thead {
        position: sticky !important;
        top: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 10;
    }
    
    .table-scrollable thead th {
        background-color: #f8f9fa !important;
        border-bottom: 2px solid #dee2e6 !important;
    }
    
    .table-scrollable tfoot {
        position: sticky !important;
        bottom: 0 !important;
        background-color: #f8f9fa !important;
        z-index: 10;
    }
    
    .table-scrollable tbody tr {
        border-bottom: 1px solid #dee2e6 !important;
    }
</style>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sale Details</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('sales.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Sales
        </a>
    </div>
</div>

<div class="row">
    <!-- Sale Information -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-receipt me-2"></i>
                    Transaction #{{ $sale->transaction_number }}
                </h5>
                <span class="badge 
                    @if($sale->status == 'completed') bg-success
                    @elseif($sale->status == 'pending') bg-warning
                    @else bg-danger @endif">
                    {{ ucfirst($sale->status) }}
                </span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Date & Time:</strong><br>{{ $sale->created_at->format('F d, Y h:i:s A') }}</p>
                        <p><strong>Cashier:</strong><br>{{ $sale->user->name }}</p>
                        <p><strong>Customer:</strong><br>{{ $sale->customer_name ?? 'Walk-in Customer' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Payment Method:</strong><br>
                            <span class="badge bg-light text-dark">{{ ucfirst($sale->payment_method) }}</span>
                        </p>
                        <p><strong>Total Amount:</strong><br>
                            <span class="fs-4 fw-bold text-primary">₱{{ number_format($sale->total_amount, 2) }}</span>
                        </p>
                    </div>
                </div>
                
                @if($sale->notes)
                    <div class="mt-3">
                        <strong>Notes:</strong>
                        <p class="mb-0">{{ $sale->notes }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Items Sold -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-basket me-2"></i>
                    Items Sold ({{ $sale->saleItems->count() }})
                </h5>
            </div>
            <div class="card-body">
                <div class="table-scrollable">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>SKU</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Discount</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleItems as $item)
                                <tr>
                                    <td>
                                        <strong>{{ $item->product->name }}</strong>
                                        @if($item->product->is_active == false)
                                            <span class="badge bg-warning ms-2">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->product->sku }}</td>
                                    <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                    <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                    <td>
                                        @if($item->discount > 0)
                                            ₱{{ number_format($item->discount, 2) }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="fw-bold">₱{{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="table-light">
                                <th colspan="5" class="text-end">Total Amount:</th>
                                <th class="fw-bold text-primary">₱{{ number_format($sale->total_amount, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions & Summary -->
    <div class="col-md-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('sales.receipt', $sale) }}" class="btn btn-info" target="_blank">
                        <i class="fas fa-print me-2"></i> Print Receipt
                    </a>
                    
                    @if($sale->status == 'pending')
                        <a href="{{ route('sales.edit', $sale) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-2"></i> Edit Sale
                        </a>
                        
                        <form action="{{ route('sales.destroy', $sale) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" 
                                    onclick="return confirm('Are you sure you want to cancel this sale? Items will be returned to inventory.')">
                                <i class="fas fa-times me-2"></i> Cancel Sale
                            </button>
                        </form>
                    @endif
                    
                    @if($sale->status == 'completed')
                        <button class="btn btn-success w-100" disabled>
                            <i class="fas fa-check me-2"></i> Sale Completed
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Payment Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>₱{{ number_format($sale->total_amount, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Discount:</span>
                        <span>₱0.00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tax:</span>
                        <span>₱0.00</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Total Paid:</strong>
                        <strong class="text-primary fs-5">₱{{ number_format($sale->total_amount, 2) }}</strong>
                    </div>
                </div>
                
                <div class="alert alert-info mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    <small>
                        Payment received via {{ ucfirst($sale->payment_method) }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        @if($sale->customer_name)
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0"><strong>Name:</strong> {{ $sale->customer_name }}</p>
                    <p class="mb-0"><strong>Type:</strong> Registered Customer</p>
                </div>
            </div>
        @else
            <div class="card">
                <div class="card-body text-center">
                    <i class="fas fa-user fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">Walk-in Customer</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
