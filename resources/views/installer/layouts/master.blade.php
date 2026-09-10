<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- FONTS -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Hind+Siliguri:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FONTAWESOME -->
    <link rel="stylesheet" href="{{ asset('themes/default/fonts/fontawesome/fontawesome.css') }}">

    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Inter', 'Hind Siliguri', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            -webkit-font-smoothing: antialiased;
        }

        .installer-card {
            width: 100%;
            max-width: 520px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.06);
            padding: 32px 28px;
        }

        /* Brand & Step Header */
        .installer-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 1px solid #f1f5f9;
        }

        .brand-title {
            font-size: 16px;
            font-weight: 700;
            color: #0f172a;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .brand-badge {
            font-size: 11px;
            font-weight: 600;
            background: #fff7ed;
            color: #ea580c;
            border: 1px solid #ffedd5;
            padding: 1px 7px;
            border-radius: 6px;
        }

        .step-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
        }

        /* Minimal Progress Bar */
        .step-segments {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 5px;
            margin-bottom: 24px;
        }

        .step-seg {
            height: 4px;
            background: #e2e8f0;
            border-radius: 2px;
            transition: background 0.2s ease;
        }

        .step-seg.active {
            background: #ea580c;
        }

        .step-seg.done {
            background: #10b981;
        }

        /* Page Title */
        .step-title {
            font-size: 17px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 18px;
        }

        /* Clean Forms */
        .form-row {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }

        .form-label .req {
            color: #ea580c;
        }

        .form-input {
            width: 100%;
            height: 40px;
            background: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            padding: 0 12px;
            font-size: 13px;
            color: #0f172a;
            font-family: inherit;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-input:focus {
            border-color: #ea580c;
            outline: none;
            box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.12);
        }

        .form-error {
            margin-top: 4px;
            font-size: 11px;
            color: #dc2626;
            font-weight: 500;
        }

        .form-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .form-grid-host {
            display: grid;
            grid-template-columns: 3fr 1fr;
            gap: 12px;
        }

        /* Alert */
        .installer-alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .installer-alert.danger {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
        }

        .installer-alert.info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            color: #475569;
        }

        /* Buttons */
        .action-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 24px;
            padding-top: 18px;
            border-top: 1px solid #f1f5f9;
        }

        .btn-submit {
            background: #ea580c;
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            padding: 9px 20px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s ease;
        }

        .btn-submit:hover {
            background: #c2410c;
        }

        .btn-submit:disabled {
            background: #94a3b8;
            cursor: not-allowed;
        }

        .btn-prev {
            background: #ffffff;
            color: #475569;
            border: 1px solid #cbd5e1;
            font-size: 13px;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s ease;
        }

        .btn-prev:hover {
            background: #f8fafc;
            color: #0f172a;
        }
    </style>

    <title>@yield('title') | Sohoj RMS</title>

    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>

<body>
    @php
        $currentRoute = Route::currentRouteName();
        $currentStep = match($currentRoute) {
            'installer.index' => 1,
            'installer.requirement' => 2,
            'installer.permission' => 3,
            'installer.site', 'installer.license' => 4,
            'installer.database' => 5,
            'installer.final', 'installer.finalStore' => 6,
            default => 1,
        };
    @endphp

    <div class="installer-card">
        <!-- Brand & Step Indicator -->
        <div class="installer-top">
            <div class="brand-title">
                <i class="fa-solid fa-utensils" style="color: #ea580c;"></i>
                <span>Sohoj RMS</span>
                <span class="brand-badge">v1.0</span>
            </div>
            <div class="step-label">ধাপ {{ $currentStep }} / 6</div>
        </div>

        <!-- 6-Segment Step Bar -->
        <div class="step-segments">
            @for($i = 1; $i <= 6; $i++)
                @php
                    $cls = '';
                    if ($i < $currentStep) {
                        $cls = 'done';
                    } elseif ($i === $currentStep) {
                        $cls = 'active';
                    }
                @endphp
                <div class="step-seg {{ $cls }}"></div>
            @endfor
        </div>

        <!-- Content -->
        @yield('container')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('themes/default/js/customScript.js') }}"></script>
</body>

</html>
