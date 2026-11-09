<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #1c1c1a; font-size: 13px; margin: 0; }
        .wrap { padding: 30px; }
        .head { display: flex; justify-content: space-between; border-bottom: 3px solid #c9a24b; padding-bottom: 14px; }
        .brand { font-size: 26px; font-weight: bold; color: #123b2e; letter-spacing: 3px; }
        .muted { color: #777; }
        h2 { color: #123b2e; font-size: 15px; margin: 22px 0 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th { background: #123b2e; color: #e2c98d; text-align: left; padding: 8px 10px; font-size: 12px; }
        td { padding: 8px 10px; border-bottom: 1px solid #eee; }
        .right { text-align: right; }
        .totals td { border: none; padding: 3px 10px; }
        .grand { font-size: 15px; font-weight: bold; color: #123b2e; }
        .info td { border: none; padding: 2px 0; }
    </style>
</head>
<body>
<div class="wrap">
    <div class="head">
        <div>
            <div class="brand">{{ setting('site_name', 'SHOP') }}</div>
            <div class="muted">{{ setting('contact_email') }}</div>
        </div>
        <div class="right">
            <div style="font-size:20px;font-weight:bold;">INVOICE</div>
            <div class="muted">#{{ $order->order_number }}</div>
            <div class="muted">{{ $order->created_at->format('d M Y') }}</div>
        </div>
    </div>

    <table class="info" style="margin-top:18px;">
        <tr>
            <td style="width:50%;vertical-align:top;">
                <h2>Billed To</h2>
                {{ $order->customer_name }}<br>
                {{ $order->phone }}<br>
                {{ $order->email }}<br>
                {{ $order->address }}, {{ $order->city }}{{ $order->state ? ', '.$order->state : '' }}<br>
                {{ $order->country }}
            </td>
            <td style="width:50%;vertical-align:top;" class="right">
                <h2>Payment</h2>
                Method: Cash on Delivery<br>
                Status: {{ ucfirst($order->status) }}
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Price</th>
                <th class="right">Qty</th>
                <th class="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="right">{{ setting('currency_symbol','Rs.') }} {{ number_format($item->price, 0) }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ setting('currency_symbol','Rs.') }} {{ number_format($item->subtotal, 0) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="width:40%;margin-left:60%;margin-top:14px;">
        <tr><td>Subtotal</td><td class="right">{{ setting('currency_symbol','Rs.') }} {{ number_format($order->subtotal, 0) }}</td></tr>
        @if($order->shipping_amount > 0)<tr><td>Shipping</td><td class="right">{{ setting('currency_symbol','Rs.') }} {{ number_format($order->shipping_amount, 0) }}</td></tr>@endif
        @if($order->tax_amount > 0)<tr><td>Tax</td><td class="right">{{ setting('currency_symbol','Rs.') }} {{ number_format($order->tax_amount, 0) }}</td></tr>@endif
        <tr><td class="grand">Total</td><td class="right grand">{{ setting('currency_symbol','Rs.') }} {{ number_format($order->total, 0) }}</td></tr>
    </table>

    <p class="muted" style="margin-top:40px;text-align:center;">Thank you for your purchase!</p>
</div>
</body>
</html>
