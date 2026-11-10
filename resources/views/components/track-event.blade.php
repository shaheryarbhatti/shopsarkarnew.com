@props([
    'event',                 // ViewContent | AddToCart | InitiateCheckout | CompletePayment
    'value' => 0,
    'currency' => 'PKR',
    'contents' => [],        // [ ['id'=>, 'name'=>, 'quantity'=>, 'price'=>], ... ]
    'defer' => true,         // fire on window load (false = fire immediately)
    'once' => null,          // unique key → fire only once per browser (e.g. order number)
])

@php
    // Map our canonical event names to Facebook's names (TikTok uses ours as-is).
    $fbMap = [
        'ViewContent' => 'ViewContent',
        'AddToCart' => 'AddToCart',
        'InitiateCheckout' => 'InitiateCheckout',
        'CompletePayment' => 'Purchase',
    ];
    $fbEvent = $fbMap[$event] ?? $event;

    $contents = array_values($contents);

    // TikTok payload
    $ttPayload = [
        'contents' => array_map(fn ($c) => [
            'content_id' => (string) ($c['id'] ?? ''),
            'content_type' => 'product',
            'content_name' => (string) ($c['name'] ?? ''),
            'quantity' => (int) ($c['quantity'] ?? 1),
            'price' => (float) ($c['price'] ?? 0),
        ], $contents),
        'value' => round((float) $value, 2),
        'currency' => $currency,
    ];

    // Facebook payload
    $fbPayload = [
        'content_ids' => array_map(fn ($c) => (string) ($c['id'] ?? ''), $contents),
        'content_type' => 'product',
        'contents' => array_map(fn ($c) => [
            'id' => (string) ($c['id'] ?? ''),
            'quantity' => (int) ($c['quantity'] ?? 1),
        ], $contents),
        'value' => round((float) $value, 2),
        'currency' => $currency,
    ];
@endphp

<script>
(function () {
    function fireTrackingEvent() {
        @if($once)
            try {
                var k = 'trk_' + @json((string) $once);
                if (localStorage.getItem(k)) { return; }
                localStorage.setItem(k, '1');
            } catch (e) {}
        @endif
        try { if (window.ttq) { ttq.track(@json($event), @json($ttPayload)); } } catch (e) {}
        try { if (window.fbq) { fbq('track', @json($fbEvent), @json($fbPayload)); } } catch (e) {}
    }
    @if($defer)
        if (document.readyState === 'complete') { fireTrackingEvent(); }
        else { window.addEventListener('load', fireTrackingEvent); }
    @else
        fireTrackingEvent();
    @endif
})();
</script>
