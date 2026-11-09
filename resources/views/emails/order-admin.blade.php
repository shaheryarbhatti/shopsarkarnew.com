@php $site = rtrim(config('app.url'), '/'); @endphp
<x-mail-layout :preheader="'New order '.$order->order_number.' — '.money($order->total)">

    <p style="margin:0 0 4px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#c9a24b; font-weight:bold;">New Order Received</p>
    <h1 style="margin:0 0 14px; font-family:Georgia,serif; font-size:24px; color:#081e18; font-weight:bold;">You’ve got a new order! 🎉</h1>
    <p style="margin:0 0 22px; color:#56605a;">
        A new order has just been placed on {{ setting('site_name', 'Shop Sarkar') }}. Here are the details.
    </p>

    {{-- Order + total highlight --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#081e18; border-radius:12px; margin-bottom:24px;">
        <tr>
            <td style="padding:18px 20px;">
                <span style="font-size:12px; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:1px;">Order Number</span><br>
                <span style="font-size:18px; font-weight:bold; color:#ffffff; letter-spacing:1px;">{{ $order->order_number }}</span>
            </td>
            <td align="right" style="padding:18px 20px;">
                <span style="font-size:12px; color:rgba(255,255,255,.55); text-transform:uppercase; letter-spacing:1px;">Total</span><br>
                <span style="font-family:Georgia,serif; font-size:22px; font-weight:bold; color:#c9a24b;">{{ money($order->total) }}</span>
            </td>
        </tr>
    </table>

    {{-- Customer details --}}
    <p style="margin:0 0 10px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#1f5141; font-weight:bold;">Customer</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f7f6f1; border-radius:12px; margin-bottom:22px;">
        <tr><td style="padding:14px 20px; font-size:14px; color:#243029; line-height:1.8;">
            <strong>{{ $order->customer_name }}</strong><br>
            📧 <a href="mailto:{{ $order->email }}" style="color:#1f5141; text-decoration:none;">{{ $order->email }}</a><br>
            📞 {{ $order->phone }}<br>
            📍 {{ $order->address }}, {{ $order->city }}@if($order->state), {{ $order->state }}@endif, {{ $order->country }}
            @if($order->note)<br><br><em style="color:#8a6d3b;">Note: {{ $order->note }}</em>@endif
            @if($order->ref_code)<br><br><span style="font-size:12px; color:#8a8f8b;">Referral: {{ $order->ref_code }}</span>@endif
        </td></tr>
    </table>

    {{-- Items --}}
    <p style="margin:0 0 10px; font-size:13px; letter-spacing:2px; text-transform:uppercase; color:#1f5141; font-weight:bold;">Items</p>
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        @foreach($order->items as $item)
            <tr>
                <td style="padding:10px 0; border-bottom:1px solid #eee; font-size:14px; color:#243029;">
                    {{ $item->name }} <span style="color:#8a8f8b;">× {{ $item->quantity }}</span>
                </td>
                <td align="right" style="padding:10px 0; border-bottom:1px solid #eee; font-size:14px; font-weight:bold; color:#081e18; white-space:nowrap;">
                    {{ money($item->subtotal) }}
                </td>
            </tr>
        @endforeach
        <tr>
            <td style="padding:12px 0; font-size:15px; font-weight:bold; color:#081e18;">Total ({{ $order->payment_method === 'cod' ? 'COD' : strtoupper($order->payment_method) }})</td>
            <td align="right" style="padding:12px 0; font-family:Georgia,serif; font-size:18px; font-weight:bold; color:#1f5141; white-space:nowrap;">{{ money($order->total) }}</td>
        </tr>
    </table>

    {{-- CTA --}}
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-top:28px;">
        <tr>
            <td align="center">
                <a href="{{ $site.'/admin/orders/'.$order->id }}"
                   style="display:inline-block; background-color:#081e18; color:#c9a24b; font-family:Arial,Helvetica,sans-serif; font-size:15px; font-weight:bold; text-decoration:none; padding:14px 34px; border-radius:9999px;">
                    View in Admin Panel &rarr;
                </a>
            </td>
        </tr>
    </table>

</x-mail-layout>
