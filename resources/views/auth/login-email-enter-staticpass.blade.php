<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Login') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/reglogin/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body @if (app()->getLocale() =='fa') class="rtl" @else class="ltr" @endif>
        <div class="card login-card">
            <div class = "title">{{__('form.login')}}</div>
            
        @if ($errors->any())

        <div class="alert-box">
         
         <div class="alert-title">
            {{ __('form.errors-title') }}
         </div>

         <ul class="alert alert-danger">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        </div>
       @endif

       
 
         <form method="POST" action="{{route('staticlogin-by-email')}}">
            @csrf

            <div class = "box label-box">
                <div>{{__('form.email')}}</div>
                <div>{{$email}}</div>
                
            </div>
            <input type="hidden" name="email" id="email" value="<?php echo $email; ?>" />
            <input type="hidden" name="back" id="back" value="<?php echo $back; ?>" />
            <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />

            <!-- Password -->
            <div class="box password-box"> 
                <input id="password" type="password" name="password" required />
                <label for="password">{{__('form.pass')}}</label>
            </div>

            <!-- Remember Me -->
            <div class="box remember-box">
                <label for="remember_me">
                    <input id="remember_me" type="checkbox" name="remember">
                    <span>{{ __('form.remember_me') }}</span>
                </label>
            </div>

            <div class="box pass-forget-box">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('form.forget_pass') }}
                    </a>
                @endif
                
            </div>

            <button id = "submit">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    {{ __('form.login') }}
            </button>
            
        </form>
        </div>
    </body>
</html> 


