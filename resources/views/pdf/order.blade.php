<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order #{{ $commande->id }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .order-info {
            margin: 10px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        th, td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f5f5f5;
            text-align: left;
        }
        .totals {
            margin-top: 20px;
            float: right;
            width: 300px;
        }
        .totals-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
        }
        .footer {
            position: absolute;
            bottom: 20px;
            width: 100%;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Restaurant Name</h1>
        <p>123 Main Street, City, Country</p>
        <p>Tel: +1 234 567 890</p>
    </div>

    <div class="order-info">
        <p><strong>Order #:</strong> {{ $commande->id }}</p>
        <p><strong>Date:</strong> {{ $commande->date }} {{ $commande->heure }}</p>
        <p><strong>Table:</strong> {{ $commande->table->nom }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $subtotal = 0;
                $totalTVA = 0;
            @endphp
            
            @foreach($commande->details as $detail)
                @php
                    $itemTotal = $detail->prix_ht * $detail->qte;
                    $tvaAmount = ($detail->tva / 100) * $itemTotal;
                    $subtotal += $itemTotal;
                    $totalTVA += $tvaAmount;
                @endphp
                <tr>
                    <td>{{ $detail->article->designation }}</td>
                    <td>{{ $detail->qte }}</td>
                    <td>{{ number_format($detail->prix_ht, 2) }} €</td>
                    <td>{{ number_format($itemTotal, 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row">
            <span>Subtotal:</span>
            <span>{{ number_format($subtotal, 2) }} €</span>
        </div>
        <div class="totals-row">
            <span>TVA Total:</span>
            <span>{{ number_format($totalTVA, 2) }} €</span>
        </div>
        <div class="totals-row" style="font-weight: bold;">
            <span>Grand Total:</span>
            <span>{{ number_format($subtotal + $totalTVA, 2) }} €</span>
        </div>
    </div>

    <div class="footer">
        Thank you for your visit! • Generated at: {{ now()->format('Y-m-d H:i') }}
    </div>
</body>
</html>