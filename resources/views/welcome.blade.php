@php
    $systemSetting = App\Models\Setting::first();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- FAVICON --}}
    <link rel="shortcut icon" type="image/x-icon"
        href="{{ isset($systemSetting->favicon) && !empty($systemSetting->favicon) ? asset($systemSetting->favicon) : asset('frontend/defult_fav_icon.png') }}" />

    <title>{{ $systemSetting->system_name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            font-family: Arial, sans-serif;
        }

        body {
            background-image: url('{{ asset('uploads/defult-image/mockup_image.png') }}');
            background-size: cover;
            background-position: top center;
            background-repeat: no-repeat;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .login-btn,
        .dashboard-btn {
            position: absolute;
            bottom: 20px;
            left: 20px;
            padding: 0.8em 2em;
            font-size: 1.2em;
            color: white;
            background-color: rgba(0, 0, 0, 0.7);
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .login-btn:hover,
        .dashboard-btn:hover {
            background-color: rgba(0, 0, 0, 0.9);
            transform: scale(1.05);
        }

        @media (max-width: 600px) {

            .login-btn,
            .dashboard-btn {
                padding: 0.6em 1.5em;
                font-size: 1em;
                bottom: 10px;
                left: 10px;
            }
        }
    </style>
</head>

<body>
    @if (Route::has('login'))
        @auth
            <a href="{{ route('dashboard') }}" class="dashboard-btn">
                Dashboard
            </a>
        @else
            <a href="{{ route('login') }}" class="login-btn">
                Log in
            </a>
        @endauth
    @endif
</body>

</html>
