<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - {{ $sale->transaction_number }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        .receipt {
            max-width: 400px;
            margin: 0 auto;
            background: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0;
            font-size: 12px;
        }
        .info {
            margin-bottom: 15px;
        }
        .info p {
            margin: 5px 0;
            font-size: 12px;
        }
        .items {
            margin-bottom: 15px;
        }
        .items table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        .items th {
            text-align: left;
            border-bottom: 1px solid #000;
            padding: 5px 0;
        }
        .items td {
            padding: 5px 0;
        }
        .items .text-right {
            text-align: right;
        }
        .total {
            border-top: 2px dashed #000;
            padding-top: 10px;
            margin-bottom: 15px;
        }
        .total p {
            margin: 5px 0;
            font-size: 12px;
            display: flex;
            justify-content: space-between;
        }
        .total .grand-total {
            font-weight: bold;
            font-size: 14px;
        }
        .footer {
            text-align: center;
            border-top: 2px dashed #000;
            padding-top: 15px;
            margin-top: 15px;
        }
        .footer p {
            margin: 5px 0;
            font-size: 11px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            font-size: 10px;
            font-weight: bold;
            border-radius: 3px;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }
        .badge-warning {
            background: #ffc107;
            color: black;
        }
        .badge-danger {
            background: #dc3545;
            color: white;
        }
        @media print {
            body { background: white; }
            .receipt { box-shadow: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>ALYMART</h1>
            <p>General Santos City</p>
            <p>Sales & Inventory System</p>
            <p>Tel: (123) 456-7890</p>
        </div>

        <!-- Transaction Info -->
        <div class="info">
            <p><strong>Receipt #:</strong> {{ $sale->transaction_number }}</p>
            <p><strong>Date:</strong> {{ $sale->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Cashier:</strong> {{ $sale->user->name }}</p>
            <p><strong>Customer:</strong> {{ $sale->customer_name ?? 'Walk-in' }}</p>
            <p><strong>Payment:</strong> {{ ucfirst($sale->payment_method) }}</p>
            <p><strong>Status:</strong> 
                <span class="badge 
                    @if($sale->status == 'completed') badge-success
                    @elseif($sale->status == 'pending') badge-warning
                    @else badge-danger @endif">
                    {{ ucfirst($sale->status) }}
                </span>
            </p>
        </div>

        <!-- Items -->
        <div class="items">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-right">Qty</th>
                        <th class="text-right">Price</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="text-right">{{ number_format($item->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total -->
        <div class="total">
            <p>
                <span>Subtotal:</span>
                <span>{{ number_format($sale->total_amount, 2) }}</span>
            </p>
            <p>
                <span>Discount:</span>
                <span>0.00</span>
            </p>
            <p>
                <span>VAT (12%):</span>
                <span>0.00</span>
            </p>
            <p class="grand-total">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total_amount, 2) }}</span>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for shopping at Alymart!</strong></p>
            <p>Please come again</p>
            <p>*** This is a computer-generated receipt ***</p>
            @if($sale->notes)
                <p><strong>Notes:</strong> {{ $sale->notes }}</p>
            @endif
        </div>
    </div>

    <script>
        // Auto print when page loads
        window.onload = function() {
            window.print();
        };
        
        // Close window after printing
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
