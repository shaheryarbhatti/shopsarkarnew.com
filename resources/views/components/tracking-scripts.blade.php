@php
    // Marketing / tracking pixels — rendered into the <head> on every front-end page.
    $fbId = trim((string) setting('facebook_pixel_id'));
    $ttId = trim((string) setting('tiktok_pixel_id'));
    $customHead = (string) setting('custom_head_code');

    $out = '';

    // ---- Meta (Facebook) Pixel ----
    if ($fbId !== '') {
        $id = e($fbId);
        $fb = <<<'FB'
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '__PIXEL_ID__');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=__PIXEL_ID__&ev=PageView&noscript=1"/></noscript>
<!-- End Meta Pixel Code -->
FB;
        $out .= str_replace('__PIXEL_ID__', $id, $fb) . "\n";
    }

    // ---- TikTok Pixel ----
    if ($ttId !== '') {
        $id = e($ttId);
        $tt = <<<'TT'
<!-- TikTok Pixel Code -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie","holdConsent","revokeConsent","grantConsent"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var r="https://analytics.tiktok.com/i18n/pixel/events.js",o=n&&n.partner;ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=r,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var i=d.createElement("script");i.type="text/javascript",i.async=!0,i.src=r+"?sdkid="+e+"&lib="+t;var a=d.getElementsByTagName("script")[0];a.parentNode.insertBefore(i,a)};
  ttq.load('__PIXEL_ID__');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- End TikTok Pixel Code -->
TT;
        $out .= str_replace('__PIXEL_ID__', $id, $tt) . "\n";
    }

    // ---- Custom head code (raw, admin-trusted) ----
    if (trim($customHead) !== '') {
        $out .= $customHead . "\n";
    }
@endphp
{!! $out !!}
