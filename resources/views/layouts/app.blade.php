<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Города</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABAWFf9PT0/qAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAE9PT4jr6Of/3tzb/09PT4gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAARFxb/6Obl/+De3f8RFxb/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABPT0+I7O7w/+jm5f/g3t3/r6yr/09PT4gAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAADhAQ//Du7f/o5uX/4N7d/9nX1v8KDg3/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEBYV//X29f/w7u3/6Obl/+De3f/Z19b/0M7N/w8VFP8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAICAgpfj6/P/19fX/8O7t/+jm5f/g3t3/2dfW/9DOzf/KxsX/29vbDgAAAAAAAAAAAAAAAAAAAAAAAAAAT09PjMTGxv/+/v7/9fX1/+rr6f8VGhn/CA0M/9nX1v/Qzs3/ysbF/woNDf9PT0+MAAAAAAAAAAAAAAAAAAAAAE9PT8b19/3//v7+//X19f8RFxb/rKan/6Sen/8SFxb/0M7N/8rGxf+XlZT/T09PxgAAAAAAAAAAAAAAAAAAAABPT0/GKR/h/yQa3v8lGdT/Hh8f/6ymp/+knp//DxgU/yYWo/8lFJr/MCJ5/09PT8YAAAAAAAAAAAAAAAAAAAAAT09PjCwh4/8kGt7/JhjU/zEkrv+4srP/SUhI/yMUrv8mFqP/JRSb/xISLv9PT0+MAAAAAAAAAAAAAAAAAAAAAAAAAAAQGBj/JRrY/yYZ1P8kGMr/JBe//yUWt/8lFq3/Jhaj/yUUmv8GBwe/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABEUGf8lGc3/JBjK/yQXv/8lFrf/JRat/yYWo/8QFhf/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAChUT/yIYy/8kF7//JRa3/ygZrP8OFhT/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAABPT0+MT09Pxk9PT8ZPT0+MAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA//8AAP5/AAD8PwAA/D8AAPgfAAD4HwAA8A8AAOAPAADAAwAAwAMAAMADAADAAwAA4AcAAPAPAAD4HwAA/D8AAA==" rel="icon" type="image/x-icon" />
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    City on new server
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif


                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')

            <div class="container" >

                <div id="city_content" class="mt-5"></div>
                @if(Route::is('main') )


                    <div class="form-check-inline newlat1">
                        <label class="form-check-label" for="radio1">
                            <input type="radio" class="form-check-input" id="radio1" name="geodata" value="old" >Старый
                        </label>
                    </div>
                    <div class="form-check-inline newlng1">
                        <label class="form-check-label" for="radio2">
                            <input type="radio" class="form-check-input" id="radio2" name="geodata" value="new" checked >Новый
                        </label>
                    </div>
                @endif
                <div class="row mt-4 mb-5">
                    <div class="col-sm-3"><div class="newlat"></div></div>
                    <div class="col-sm-3"><div class="newlng"></div></div>


                    <div class="col-sm-3"><div id="button" class="somediv"></div></div>
                    <div class="col-sm-3"><div id="mapGoogle" style="width:540px; height:300"></div></div>
                </div>
                <div id="mapYandex" style="width:540px; height:300"></div>

            </div>


        </main>
    </div>
 <div class="mt-5"></div>
</body>
</html>
