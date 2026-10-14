@php
    $hexToChannels = function ($hex, $fallback) {
        $hex = ltrim((string) $hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (!preg_match('/^[0-9A-Fa-f]{6}$/', $hex)) {
            return $fallback;
        }
        return hexdec(substr($hex, 0, 2)) . ' ' . hexdec(substr($hex, 2, 2)) . ' ' . hexdec(substr($hex, 4, 2));
    };
    $themePrimary = $hexToChannels(Settings::group('theme')->get('theme_primary_color'), '249 115 22');
    $resortName = Settings::group('resort')->get('resort_name');
    $companyName = Settings::group('company')->get('company_name');
    if (is_array($companyName)) {
        $companyName = reset($companyName);
    }
    $brandTitle = $resortName ?: ($companyName ?: config('app.name'));
@endphp
<!DOCTYPE html>
{{-- DYNAMIC THEME COLOR: set as an inline style attribute (not a <style> block) so it always
     wins over app.css's compiled ":root{--primary:...}" default no matter when/how that
     stylesheet is injected. Vite's dev server loads CSS via an async JS module that appends
     its <style> tag to <head> at runtime, which can land AFTER a synchronous <style> block and
     silently override it back to the default on every hard reload. An inline style attribute
     on the element itself always beats any external/injected stylesheet rule regardless of
     DOM order or timing. --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="--primary: {{ $themePrimary }};">

<head>
    <!-- REQUIRED META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/fontawesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/lab/lab.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/public/public.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/rubik/rubik.css') }}">

    <!-- THEME RESTORATION SCRIPT -->
    <script>
        if (localStorage.getItem('rms_theme') === 'dark' || (!('rms_theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- CUSTOM STYLE -->
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">
    <!-- PAGE TITLE -->

    <title>{{ $brandTitle }}</title>

    <!-- FAV ICON -->
    <link rel="icon" type="image" href="{{ $favicon }}">

    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::HEAD)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif
</head>

<body>
    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::BODY)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

    <div id="app">
        <default-component>
            <div id="rms-initial-preloader" style="position: fixed; inset: 0; display: flex; align-items: center; justify-content: center; background: #faf8f5; z-index: 99999;" class="dark:bg-[#0f1a12]">
                <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 28px 36px; border-radius: 24px; background: rgba(255, 255, 255, 0.98); box-shadow: 0 20px 45px -10px rgba(18, 31, 21, 0.12); border: 1px solid rgba(201, 163, 78, 0.28);">
                    <div style="position: relative; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <div style="position: absolute; inset: 0; border-radius: 50%; border: 3px solid transparent; border-top-color: #c9a34e; border-right-color: #1b2e20; animation: rmsSpin 1.1s cubic-bezier(0.55, 0.15, 0.45, 0.85) infinite;"></div>
                        <div style="position: absolute; inset: 6px; border-radius: 50%; border: 2px dashed rgba(201, 163, 78, 0.35); animation: rmsSpinReverse 4s linear infinite;"></div>
                        <div style="width: 48px; height: 48px; border-radius: 50%; background: linear-gradient(135deg, #1b2e20 0%, #101c13 100%); display: flex; align-items: center; justify-content: center; color: #c9a34e; box-shadow: 0 4px 14px rgba(27, 46, 32, 0.3);">
                            <svg style="width: 24px; height: 24px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-4M9 9h1M9 13h1M9 17h1"/>
                            </svg>
                        </div>
                    </div>
                    <div style="margin-top: 16px; text-align: center;">
                        <span style="font-size: 1.05rem; font-weight: 700; color: #172419; letter-spacing: -0.2px; display: block;">{{ $brandTitle }}</span>
                        <div style="font-size: 0.8rem; font-weight: 500; color: #667568; margin-top: 4px;">লোড হচ্ছে...</div>
                    </div>
                </div>
            </div>
            <style>
                @keyframes rmsSpin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
                @keyframes rmsSpinReverse { 0% { transform: rotate(360deg); } 100% { transform: rotate(0deg); } }
            </style>
        </default-component>
    </div>

    @if (!blank($analytics))
        @foreach ($analytics as $analytic)
            @if (!blank($analytic->analyticSections))
                @foreach ($analytic->analyticSections as $section)
                    @if ($section->section == \App\Enums\AnalyticSection::FOOTER)
                        {!! $section->data !!}
                    @endif
                @endforeach
            @endif
        @endforeach
    @endif

    @vite('resources/js/app.js')

    <script>
        const APP_URL = "{{ env('VITE_HOST') }}";
        const APP_KEY = "{{ env('VITE_API_KEY', '') }}";
        const GOOGLE_TOKEN = "{{ env('VITE_GOOGLE_MAP_KEY') }}";
        const APP_DEMO = "{{ env('VITE_DEMO') }}";
    </script>

    <script defer src="{{ asset('themes/default/js/drawer.js') }}"></script>
    <script defer src="{{ asset('themes/default/js/modal.js') }}"></script>
    <script defer src="{{ asset('themes/default/js/customScript.js') }}"></script>
    <script defer src="{{ asset('themes/default/js/tabs.js') }}"></script>
    <script defer src="{{ asset('themes/default/js/dropdown.js') }}"></script>
    <script defer src="{{ asset('themes/default/js/apexcharts/apexcharts.min.js') }}"></script>
</body>

</html>
