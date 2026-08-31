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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    <title>{{ is_array($companyName = Settings::group('company')->get('company_name')) ? (reset($companyName) ?: config('app.name')) : ($companyName ?: config('app.name')) }}</title>

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
        <default-component />
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

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script>
        const APP_URL = "{{ env('VITE_HOST') }}";
        const APP_KEY = "{{ env('VITE_API_KEY') }}";
        const GOOGLE_TOKEN = "{{ env('VITE_GOOGLE_MAP_KEY') }}";
        const APP_DEMO = "{{ env('VITE_DEMO') }}";
    </script>

    <script src="{{ asset('themes/default/js/drawer.js') }}"></script>
    <script src="{{ asset('themes/default/js/modal.js') }}"></script>
    <script src="{{ asset('themes/default/js/customScript.js') }}"></script>
    <script src="{{ asset('themes/default/js/tabs.js') }}"></script>
    <script src="{{ asset('themes/default/js/dropdown.js') }}"></script>
    <script src="{{ asset('themes/default/js/apexcharts/apexcharts.min.js') }}"></script>
</body>

</html>
