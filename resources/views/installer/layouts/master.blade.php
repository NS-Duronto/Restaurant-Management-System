<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- REQUIRED META TAGS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS -->
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/fontawesome/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/public/public.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/typography/rubik/rubik.css') }}">

    <!-- CUSTOM STYLE -->
    <link rel="stylesheet" href="{{ asset('themes/default/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('themes/default/css/custom.css') }}">

    <style>
        :root {
            --primary: 249 115 22;
        }
        .bg-primary {
            background-color: #f97316 !important;
            transition: all 0.2s ease-in-out;
        }
        .bg-primary:hover {
            background-color: #ea580c !important;
        }
        .text-primary {
            color: #f97316 !important;
        }
        .border-primary {
            border-color: #f97316 !important;
        }
        .installer-track li.active i {
            border-color: #f97316 !important;
            background-color: #f97316 !important;
            color: #ffffff !important;
        }
        .installer-track li.done::after {
            background-color: #f97316 !important;
        }
        .installer-track li.done i {
            border-color: #f97316 !important;
            color: #f97316 !important;
            background-color: #ffffff !important;
        }
        input:focus, select:focus {
            border-color: #f97316 !important;
            outline: none !important;
            box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
        }
        .brand-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 6px 14px;
            background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%);
            border: 1px solid #fed7aa;
            border-radius: 9999px;
            color: #c2410c;
            font-weight: 600;
            font-size: 0.875rem;
        }
    </style>

    <title>
        @if (trim($__env->yieldContent('template_title')))
            @yield('template_title')
            |
        @endif {{ trans('installer.title') }}
    </title>
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body class="bg-installer bg-no-repeat bg-cover bg-center">
    <div id="step-group" class="w-screen h-screen  overflow-y-auto p-3 sm:p-10">
        <div id="steps" class="block w-[550px] mx-auto overflow-hidden rounded-xl shadow-paper p-8 bg-white">
            <div class="text-center mb-4">
                <span class="brand-badge">
                    <i class="fa-solid fa-utensils"></i> Sohoj RMS
                </span>
            </div>
            <h3 class="text-lg font-semibold capitalize text-center mb-7">@yield('title')</h3>
            @yield('container')
        </div>
    </div>

    <script src="{{ asset('themes/default/js/customScript.js') }}"></script>
    <script src="{{ asset('themes/default/js/installer.js') }}"></script>
</body>

</html>
