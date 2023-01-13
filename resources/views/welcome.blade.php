<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
            .bg-darkBlue {
                background-color: #2E3454;
            }
            .main-bg {
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            .links > a {
                color: #ffffff;
            }
            .poke-api-logo {
                position: relative;
                z-index: 2;
            }
            .force-center {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%,-50%);
                z-index: 1;
            }
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height bg-darkBlue">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a class="text-white" href="{{ url('/home') }}">Home</a>
                    @else
                        <a class="text-white" href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a class="text-white" href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
            
            <div class="main-bg">
                <img class="poke-api-logo" src="{{ asset('img/poke-api-logo.png') }}">
                <img class="force-center" src="https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/249.png">
            </div>
        </div>
    </body>
</html>
