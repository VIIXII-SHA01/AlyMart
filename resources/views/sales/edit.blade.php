@extends('layouts.app')

@section('header', 'Edit Sale - ' . $sale->transaction_number)

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
    <h1 class="h2">Edit Sale</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Sale
        </a>
    </div>
</div>

<div class="row">
    <!-- Edit Form -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Edit Sale Details</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('sales.update', $sale) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Warning Message -->
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Note:</strong> You can only edit the status and notes of this sale. 
                        Item quantities and prices cannot be modified for completed sales.
                    </div>

                    <!-- Customer Information -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="customer_name" class="form-label">Customer Name</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" 
                                   value="{{ $sale->customer_name }}" placeholder="Walk-in customer">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="pending" {{ $sale->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $sale->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $sale->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Current Items (Read-only) -->
                    <div class="mb-3">
                        <label class="form-label">Current Items (Cannot be modified)</label>
                        <div class="table-scrollable">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($sale->items as $item)
                                        <tr>
                                            <td>{{ $item->product->name }}</td>
                                            <td>₱{{ number_format($item->unit_price, 2) }}</td>
                                            <td>{{ $item->quantity }} {{ $item->product->unit }}</td>
                                            <td>₱{{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-light">
                                        <th colspan="3" class="text-end">Total Amount:</th>
                                        <th class="fw-bold text-primary">₱{{ number_format($sale->total_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Add any notes about this sale...">{{ $sale->notes }}</textarea>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Update Sale
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sale Summary -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sale Summary</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <p><strong>Transaction #:</strong><br>{{ $sale->transaction_number }}</p>
                    <p><strong>Date & Time:</strong><br>{{ $sale->created_at->format('F d, Y h:i A') }}</p>
                    <p><strong>Cashier:</strong><br>{{ $sale->user->name }}</p>
                    <p><strong>Payment Method:</strong><br>
                        <span class="badge bg-light text-dark">{{ ucfirst($sale->payment_method) }}</span>
                    </p>
                    <p><strong>Total Amount:</strong><br>
                        <span class="fs-4 fw-bold text-primary">₱{{ number_format($sale->total_amount, 2) }}</span>
                    </p>
                </div>

                @if($sale->status == 'completed')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Completed Sale</strong><br>
                        <small>This sale has been completed and inventory has been updated.</small>
                    </div>
                @elseif($sale->status == 'pending')
                    <div class="alert alert-warning">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Pending Sale</strong><br>
                        <small>This sale is still pending and can be cancelled.</small>
                    </div>
                @else
                    <div class="alert alert-danger">
                        <i class="fas fa-times-circle me-2"></i>
                        <strong>Cancelled Sale</strong><br>
                        <small>This sale has been cancelled and items were returned to inventory.</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
