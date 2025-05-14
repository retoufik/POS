<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Kitchen Ticket #{{ $commande->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 80mm; /* Standard receipt width */
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 10px;
            padding: 5px;
            border-bottom: 1px dashed #000;
        }
        .ticket-info {
            margin-bottom: 10px;
            padding: 5px;
        }
        .ticket-items {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .ticket-items th, .ticket-items td {
            padding: 5px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .message {
            color: #FF0000;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            padding: 5px;
            border-top: 1px dashed #000;
            font-size: 10pt;
        }
        .important {
            font-weight: bold;
            font-size: 14pt;
        }
        .observation {
            padding: 5px;
            margin-top: 10px;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>KITCHEN TICKET</h2>
            <p class="important">ORDER #{{ $commande->id }}</p>
            <p>{{ $commande->date }}</p>
        </div>
        
        <div class="ticket-info">
            <p><strong>Type:</strong> {{ $commande->type->type }}</p>
            <p><strong>Table:</strong> {{ $commande->table->Numero }}</p>
        </div>
        
        <table class="ticket-items">
            <thead>
                <tr>
                    <th>QTY</th>
                    <th>ITEM</th>
                    <th>MESSAGE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($commande->details as $detail)
                <tr>
                    <td>{{ $detail->qte }}</td>
                    <td>{{ $detail->article->designation }}</td>
                    <td class="message">
                        @if($detail->message)
                            {{ $detail->message->message }}
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($commande->observation)
        <div class="observation">
            <p><strong>Observation:</strong></p>
            <p>{{ $commande->observation }}</p>
        </div>
        @endif
        
        <div class="footer">
            <p>PLEASE PREPARE ASAP</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>
</body>
</html>