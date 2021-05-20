<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'ROOF') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam:wght@100;300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">{{--  The apps main css file  --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <!-- Template CSS Files -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
{{--    <link rel="stylesheet" href="{{ asset('css/line-awesome.min.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/animated-headline.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/jquery.fancybox.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ asset('css/chosen.min.css') }}">--}}


    <link rel="stylesheet" href="{{ asset('css/style.css') }}">{{--  This is the css file that we bought  --}}
    <link rel="stylesheet" href="{{ asset('css/elementui/index.css') }}">{{--  Element UI css  --}}
    <link rel="stylesheet" href="{{ asset('css/elementui/input.css') }}">{{--	css for Element Autocomplete component--}}
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
</head>
<body>
    <div id="app">
        {{--            NAVIGATION --}}
        @include('layouts.navigationNew')

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>

        {{--        FOOTER--}}
        @include('layouts.footer')
    </div>

    <script>
        /**
         * Next, we will create a fresh Vue application instance and attach it to
         * the page. Then, you may begin adding components to this application
         * or customize the JavaScript scaffolding to fit your unique needs.
         */

        //EVENT BUS CREATING
        //https://www.techalyst.com/posts/vuejs-global-event-bus-and-component-communication-laravel-full-stack-development
        Vue.prototype.$eventBus = new Vue(); // Global event bus

//this was cut from app.js, in order to be able to get data from Laravel. We needed a global information about
//the user's status, if the user is logged in. Now this is something that only Laravel knows. But, there is
// a way how we can provide this info from Laravel to Vue. And that is the provide/inject story...
//https://v3.vuejs.org/guide/component-provide-inject.html
//As a part of this story, we had to move the base script below from the app.js to the main blade.
        const app = new Vue({
            el: '#app',
            provide: {//here we use the provide. The incect part is in the AdListSearchBar.vue, this is
                //the component that needs to know if the user is logged in.
                isAuthenticated: {{ json_encode(auth()->id() > 0) }}//(auth()->id() > 0) means: Laravel, give me the
                //users id. Obviously, there will be a user's id only if the user is logged in. Otherwise this will be null.
                //And if there is a users id, it will be obviously bigger than zero. So the '>' part is a clever way to
                //qiuckly transform all this into true/false, which we actually need.
            }
        });
    </script>
</body>
</html>
