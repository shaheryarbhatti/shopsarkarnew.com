@php $site = rtrim(config('app.url'), '/'); @endphp
<x-mail-layout :preheader="'Thank you! Your order '.$order->order_number.' has been received.'">

    {{-- Greeting --}}
    <p style="margin:0 0 4px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#c9a24b; font-weight:bold;">Order Confirmed</p>
    <h1 style="margin:0 0 14px; font-family:Georgia,serif; font-size:24px; color:#081e18; font-weight:bold;">Thank you, {{ $order->customer_name }}!</h1>
    <p style="margin:0 0 22px; color:#56605a;">
        We’ve received your order and our team is preparing it with care. You’ll get another update as soon as it ships.
    </p>

    {{-- Order meta card --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f6f1; border-radius:12px; margin-bottom:24px;">
        <tr>
            <td style="padding:16px 20px;">
                <span style="font-size:12px; color:#8a8f8b; text-transform:uppercase; letter-spacing:1px;">Order Number</span><br>
                <span style="font-size:17px; font-weight:bold; color:#081e18; letter-spacing:1px;">{{ $order->order_number }}</span>
            </td>
            <td align="right" style="padding:16px 20px;">
                <span style="font-size:12px; color:#8a8f8b; text-transform:uppercase; letter-spacing:1px;">Order Date</span><br>
                <span style="font-size:15px; color:#243029;">{{ $order->created_at->format('d M Y') }}</span>
            </td>
        </tr>
    </table>

    {{-- Items --}}
    <p style="margin:0 0 10px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#1f5141; font-weight:bold;">Your Items</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        @foreach($order->items as $item)
            <tr>
                <td style="padding:12px 0; border-bottom:1px solid #eee;">
                    <span style="font-size:15px; font-weight:bold; color:#243029;">{{ $item->name }}</span><br>
                    <span style="font-size:13px; color:#8a8f8b;">{{ money($item->price) }} × {{ $item->quantity }}</span>
                </td>
                <td align="right" style="padding:12px 0; border-bottom:1px solid #eee; font-size:15px; font-weight:bold; color:#081e18; white-space:nowrap;">
                    {{ money($item->subtotal) }}
                </td>
            </tr>
        @endforeach
    </table>

    {{-- Totals --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:16px;">
        <tr><td style="padding:4px 0; color:#56605a; font-size:14px;">Subtotal</td><td align="right" style="padding:4px 0; color:#243029; font-size:14px;">{{ money($order->subtotal) }}</td></tr>
        @if($order->shipping_amount > 0)
            <tr><td style="padding:4px 0; color:#56605a; font-size:14px;">Shipping</td><td align="right" style="padding:4px 0; color:#243029; font-size:14px;">{{ money($order->shipping_amount) }}</td></tr>
        @else
            <tr><td style="padding:4px 0; color:#56605a; font-size:14px;">Shipping</td><td align="right" style="padding:4px 0; color:#2f8a5b; font-size:14px; font-weight:bold;">FREE</td></tr>
        @endif
        @if($order->tax_amount > 0)
            <tr><td style="padding:4px 0; color:#56605a; font-size:14px;">Tax</td><td align="right" style="padding:4px 0; color:#243029; font-size:14px;">{{ money($order->tax_amount) }}</td></tr>
        @endif
    </table>

    {{-- Total bar --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:14px; background-color:#081e18; border-radius:12px;">
        <tr>
            <td style="padding:16px 20px; color:#ffffff; font-size:16px; font-weight:bold;">Total</td>
            <td align="right" style="padding:16px 20px; color:#c9a24b; font-family:Georgia,serif; font-size:22px; font-weight:bold;">{{ money($order->total) }}</td>
        </tr>
    </table>

    {{-- Delivery details --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:24px;">
        <tr valign="top">
            <td width="50%" style="padding-right:8px;">
                <p style="margin:0 0 6px; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#8a8f8b;">Delivery Address</p>
                <p style="margin:0; color:#243029; font-size:14px; line-height:1.6;">
                    {{ $order->customer_name }}<br>
                    {{ $order->address }}<br>
                    {{ $order->city }}@if($order->state), {{ $order->state }}@endif<br>
                    {{ $order->country }}<br>
                    {{ $order->phone }}
                </p>
            </td>
            <td width="50%" style="padding-left:8px;">
                <p style="margin:0 0 6px; font-size:12px; letter-spacing:1px; text-transform:uppercase; color:#8a8f8b;">Payment Method</p>
                <p style="margin:0; color:#243029; font-size:14px;">Cash on Delivery (COD)</p>
            </td>
        </tr>
    </table>

    {{-- CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:30px;">
        <tr>
            <td align="center">
                <a href="{{ $site.'/order/'.$order->order_number.'/success' }}"
                   style="display:inline-block; background-color:#c9a24b; color:#081e18; font-family:Arial,Helvetica,sans-serif; font-size:15px; font-weight:bold; text-decoration:none; padding:14px 34px; border-radius:9999px;">
                    View Your Order &rarr;
                </a>
            </td>
        </tr>
    </table>

    <p style="margin:28px 0 0; color:#8a8f8b; font-size:13px; text-align:center;">
        Questions? Just reply to this email — we’re happy to help. 🖤
    </p>

</x-mail-layout>
