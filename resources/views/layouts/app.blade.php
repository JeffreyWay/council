<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
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
            'signedIn' => Auth::check(),
            'broadcaster' => config('broadcasting.default'),
            'pusher' => [
                'key' => config('broadcasting.connections.pusher.key', null),
                'options' => config('broadcasting.connections.pusher.options')
            ]
        ]) !!};
    </script>

    <script src='https://www.google.com/recaptcha/api.js'></script>
    
    @if (config('broadcasting.default') === 'redis')
      <script src="//{{ Request::getHost() }}:6001/socket.io/socket.io.js"></script>
    @endif

    @yield('head')
</head>

<body class="font-sans bg-grey-lighter">
    <div id="app">
        @include ('layouts.nav')

        <div class="container mx-auto">
            <div class="flex">
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

        @include('modals.all')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @yield('scripts')
</body>
</html>
