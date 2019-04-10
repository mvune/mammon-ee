<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="/favicon.png">

    <title>
        @hasSection('title') @yield('title') | @endif
        {{ config('app.name') }}
    </title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Muli" rel="stylesheet">
    @yield('fonts')

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset(mix('css/main.css')) }}">
    @yield('styles')
</head>

<body @if (Route::is('login') || Route::is('register') || Route::is('password.request') || Route::is('password.reset')) class="alt-bg"@endif>
    <div id="top">
        @include('layouts.header')

        {{-- Flash Message --}}
        @if (session()->has('flash_message'))
        <div class="alert alert-warning alert-dismissible alert-main alert-main-auto-dismiss fade show" role="alert">
            {{ session('flash_message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="{{ asset(mix('js/bootstrap.js')) }}"></script>
    @stack('scripts')
</body>

</html>
