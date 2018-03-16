<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600" rel="stylesheet">

    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.App = {!! json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
        ]) !!};
    </script>

    <script src='https://www.google.com/recaptcha/api.js'></script>

    @yield('head')
</head>

<body class="font-sans bg-grey-lighter h-full">
    <div id="app" class="flex flex-col min-h-full">
        @include ('layouts.nav')

        <div class="container mx-auto flex flex-1">
            <div class="flex flex-1">
                @section('sidebar')
                    @include('sidebar')
                @show

                <div class="px-10 bg-white flex-1">
                    @yield('content')
                </div>

                 @include('channels-sidebar')
            </div>
        </div>

        <flash message="{{ session('flash') }}"></flash>

        <div v-cloak>
            @include('modals.all')
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
