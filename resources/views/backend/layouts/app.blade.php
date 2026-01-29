<!DOCTYPE html>
@if(config('app.display_type') == 'rtl' || (session()->has('display_type') && session('display_type') == 'rtl'))
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">

    @else
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

        @endif
        {{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">--}}
        {{--@else--}}
        {{--<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">--}}
        {{--@endlangrtl--}}
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
            <meta name="csrf-token" content="{{ csrf_token() }}">
            <!-- <title>@yield('title', app_name())</title> -->
            <title>@yield('title', env('APP_NAME'))</title>
            <meta name="description" content="@yield('meta_description', 'Laravel 5 Boilerplate')">
            <meta name="author" content="@yield('meta_author', 'Anthony Rappa')">
            @if(config('favicon_image') != "")
                <link rel="shortcut icon" type="image/x-icon"
                      href="{{asset('newassets/img/favicon.png')}}"/>
            @endif
            @yield('meta')
            <link rel="stylesheet" href="{{asset('css/select2.min.css')}}">
            <link rel="stylesheet" href="{{asset('assets/css/fontawesome-all.css')}}">

            <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

            <link rel="stylesheet"
                  href="//cdn.datatables.net/1.10.9/css/jquery.dataTables.min.css"/>
            <link rel="stylesheet"
                  href="https://cdn.datatables.net/select/1.2.0/css/select.dataTables.min.css"/>
            <link rel="stylesheet"
                  href="//cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css"/>
            {{--<link rel="stylesheet"--}}
            {{--href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.5.0/css/bootstrap-datepicker.standalone.min.css"/>--}}
            {{-- See https://laravel.com/docs/5.5/blade#stacks for usage --}}



@yield('page_css')
<style>
    span.required {
    color: #FF5722;
}
span.green-text {
    color: #4CAF50;
    font-weight: 700;
}
span.red-text {
    color: #FF5722;
    font-weight: 700;
}
@media screen and (max-width: 640px) {
    div.dt-buttons {
         float: right !important; 
        text-align: center;
    }
}
</style>

            @stack('before-styles')

        <!-- Check if the language is set to RTL, so apply the RTL layouts -->
            <!-- Otherwise apply the normal LTR layouts -->
            @vite(['resources/sass/backend/app.scss', 'resources/js/backend.js'])

            @stack('after-styles')

            @if((config('app.display_type') == 'rtl') || (session('display_type') == 'rtl'))
                <style>
                    .float-left {
                        float: right !important;
                    }

                    .float-right {
                        float: left !important;
                    }
                </style>
            @endif

        </head>

        <body class="{{ config('backend.body_classes') }}">
        @include('backend.includes.header')

        <div class="app-body">
            @include('backend.includes.sidebar')

            <main class="main">
                @include('includes.partials.logged-in-as')
                {{--{!! Breadcrumbs::render() !!}--}}

                <div class="container-fluid" style="padding-top: 30px">
                    <div class="animated fadeIn">
                        <div class="content-header">
                            @yield('page-header')
                        </div><!--content-header-->

                        @include('includes.partials.messages')
                        @yield('content')
                    </div><!--animated-->
                </div><!--container-fluid-->
            </main><!--main-->

            {{--@include('backend.includes.aside')--}}
        </div><!--app-body-->

        @include('backend.includes.footer')

        <!-- Scripts -->
        @stack('before-scripts')
        <script>
            //Route for message notification
            var messageNotificationRoute = '{{route('admin.messages.unread')}}'
        </script>
        <script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
        <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
        <script src="{{asset('js/pdfmake.min.js')}}"></script>
        <script src="{{asset('js/vfs_fonts.js')}}"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
        <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


        <script src="{{asset('js/select2.full.min.js')}}" type="text/javascript"></script>
        
         @yield('page_js')
        
        @if($logged_in_user->hasRole('teacher'))
        <!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/64c0c0ffcc26a871b02b47e3/1h68f3ql4';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>

@endif
<!--End of Tawk.to Script-->



        <script src="{{asset('js/main.js')}}" type="text/javascript"></script>
        <script>
            window._token = '{{ csrf_token() }}';
        </script>
        @stack('after-scripts')

        </body>
        </html>
