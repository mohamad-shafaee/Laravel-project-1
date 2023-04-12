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
            
            <!-- Session Status 
        <x-auth-session-status class="mb-4" :status="session('status')" />
                                                                            -->

        <!-- Validation Errors 
        <x-auth-validation-errors class="mb-4" :errors="$errors" />
                                                                     --> 

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

            <form method="POST" action="#">
            @csrf

            <!-- Email Address -->
            <div class="box phone-box">
                 
                <input id="phone-email-input" class="phone-email" name="pe" type="text" value="@if(old('phone')) {{old('phone')}} @elseif(old('email')) {{old('email')}} @endif" required />
                <input type="hidden" name="back" id="back" value="<?php echo $back; ?>" />
                <input type="hidden" name="id" id="id" value="<?php echo $id; ?>" />
                <label id= "phone-or-email-label" for="phone-email-input" >{{__('form.phone-or-email')}}</label>
            </div>


            <div class="box goto-box">
                

                <button name="action" value="static-p" class="static-pass">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    {{ __('form.static-login') }}
                </button>

                <button name="action" value="dynamic-p" class="dynamic-pass">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    {{ __('form.dynamic-login') }}
                </button>

                <div class="alert alert-login"> </div>

                @if (Route::has('password.request'))
                    <a class="pass-req" href="{{ route('password.request') }}">
                        {{ __('form.forget_pass') }}
                    </a>
                @endif
            </div>
            
        </form>
        </div>
    </body>
</html>
 
<script type="text/javascript">




    document.addEventListener("DOMContentLoaded", function(){

    var btnStatic  = document.querySelector('button.static-pass');
    var btnDynamic = document.querySelector('button.dynamic-pass');

    btnStatic.addEventListener('click', function(event){
        event.preventDefault();

        var inputpe = document.querySelector('form input.phone-email');
        console.log(inputpe.value);
        
        

        var inVal = inputpe.value;
       // inputpe.setAttribute('name', 'phone');

        if(!isNaN(inVal)){
            //A phone number entered
            inputpe.name = 'phone';

            let form1 = document.querySelector("form");
                form1.action = "{{route('enter-staticly-by-phone')}}";
                form1.submit();
            

         /* $(this).closest('form')
            .attr('action', "{{route('enter-staticly-by-phone')}}")
            .submit();*/


        }else{
            //probably an email entered
             inputpe.name = 'email';

             let form1 = document.querySelector("form");
                form1.action = "{{route('enter-staticly-by-email')}}";
                form1.submit();


            /*$(this).closest('form')
            .attr('action', "{{route('enter-staticly-by-email')}}")
            .submit();*/



        }

    });


    ////////


     btnDynamic.addEventListener('click', function(event){
        event.preventDefault();

        var inputpe = document.querySelector('form input.phone-email');
        console.log(inputpe.value);
        
        

        var inVal = inputpe.value;
       // inputpe.setAttribute('name', 'phone');

        if(!isNaN(inVal)){
            //A phone number entered
            inputpe.name = 'phone';

            let form1 = document.querySelector("form");
                form1.action = "{{route('enter-dynamicly-by-phone')}}";
                form1.submit();


        }else{
            //probably an email entered
             //inputpe.name = 'email';

            alert = document.querySelector("form .alert.alert-login");
            alert.innerHTML = "{{__('auth.you-should-enter-phone')}}";

        }

    });


});


</script>


