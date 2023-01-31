<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">



<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

       <!-- JS-->
       <script src="{{ asset('js/app.js') }}" defer></script>

       <!-- Fonts -->
       <link rel="dns-prefetch" href="//fonts.gstatic.com">
       <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
        <!-- Select2 CSS & Js -->
       <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet"/>


       <!-- CSS theme files -->
        <link href="{{ asset('assets/css/dashlite.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/theme.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{asset('assets/css/custom.css')}}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="nk-body bg-lighter npc-general has-sidebar">
        <div class="nk-app-root">
            <div class="nk-main ">
                @include('layouts.partials.sidebar')
                    <div class='nk-wrap'>
                        @include('layouts.partials.header')
                        <!-- content @s -->
                        <div class="nk-content ">
                            <div class="container-fluid">
                                <div class="nk-content-inner">
                                    <div class="nk-content-body">
                                        <div class="components-preview wide-md mx-auto">
                                            <!-- Page Content -->
                                            @yield('content')
                                            @yield('content2')
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- content @e -->
                        @include('layouts.partials.footer')
                    </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
        {{-- <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"> --}}
        <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
        {{-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> --}}
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
        {{-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}

        {{-- Sweet Alerts --}}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- Theme JS files --}}
        <script src="{{ asset('assets/js/bundle.js') }}"></script>
        <script src="{{ asset('assets/js/scripts.js') }}"></script>
        <script src="{{ asset('assets/libs/datatable-btns.js') }}"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

        @stack('scripts')

    </body>
</html>
