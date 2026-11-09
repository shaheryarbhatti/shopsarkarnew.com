@props(['preheader' => ''])
@php
    $name  = setting('site_name', 'Shop Sarkar');
    $phone = setting('contact_phone');
    $email = setting('contact_email', 'support@shopsarkar.com');
    $site  = rtrim(config('app.url'), '/');

    // Absolute logo URL (email clients need a full URL, not a relative path).
    $logoRel = setting_file('site_logo');
    $logoUrl = $logoRel
        ? (\Illuminate\Support\Str::startsWith($logoRel, ['http://', 'https://']) ? $logoRel : $site . $logoRel)
        : null;
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $name }}</title>
</head>
<body style="margin:0; padding:0; width:100%; background-color:#eceae2; -webkit-font-smoothing:antialiased;">
    @if($preheader)
        <span style="display:none !important; visibility:hidden; opacity:0; color:transparent; height:0; width:0; overflow:hidden; mso-hide:all;">{{ $preheader }}</span>
    @endif

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#eceae2;">
        <tr>
            <td align="center" style="padding:28px 12px;">

                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="width:600px; max-width:600px; background-color:#ffffff; border-radius:16px; overflow:hidden; box-shadow:0 8px 30px rgba(8,30,24,.08);">

                    {{-- Header --}}
                    <tr>
                        <td align="center" style="background-color:#081e18; padding:30px 28px;">
                            @if($logoUrl)
                                <img src="{{ $logoUrl }}" alt="{{ $name }}" height="66" style="height:66px; width:auto; max-width:240px; display:inline-block; border:0; outline:none; text-decoration:none;">
                            @else
                                <div style="font-family:Georgia,'Times New Roman',serif; font-size:28px; font-weight:bold; letter-spacing:5px; color:#c9a24b; text-transform:uppercase;">{{ $name }}</div>
                            @endif
                            <div style="margin-top:12px; font-family:Arial,Helvetica,sans-serif; font-size:11px; letter-spacing:3px; color:rgba(255,255,255,.55); text-transform:uppercase;">Luxury Fragrances</div>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:36px 32px; font-family:Arial,Helvetica,sans-serif; color:#243029; font-size:15px; line-height:1.6;">
                            {{ $slot }}
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f7f3e9; padding:24px 32px; text-align:center; font-family:Arial,Helvetica,sans-serif;">
                            <div style="font-family:Georgia,serif; font-size:16px; letter-spacing:3px; color:#1f5141; font-weight:bold; text-transform:uppercase;">{{ $name }}</div>
                            <div style="margin-top:8px; font-size:12px; color:#8a8f8b; line-height:1.7;">
                                @if($email)<a href="mailto:{{ $email }}" style="color:#1f5141; text-decoration:none;">{{ $email }}</a>@endif
                                @if($phone) &nbsp;•&nbsp; {{ $phone }} @endif
                            </div>
                            <div style="margin-top:14px; font-size:11px; color:#b0b4b0;">
                                &copy; {{ date('Y') }} {{ $name }}. All rights reserved.
                            </div>
                        </td>
                    </tr>

                </table>

                <div style="font-family:Arial,Helvetica,sans-serif; font-size:11px; color:#9a9e9a; margin-top:16px;">
                    Smell the power. Feel the royalty.
                </div>

            </td>
        </tr>
    </table>
</body>
</html>
