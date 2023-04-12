<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 


    </head>
    <body  @if (app()->getLocale() =='fa') class="rtl" @else class="ltr" @endif>
        <div class="container">
            <!-- Flash messages to users It is not implemented yet! -->
            <flash-wrapper ref='flashes'></flash-wrapper> 

            <!-- Page Heading -->
            <header >
                <x-header> </x-header>
            </header>

            <!-- Page Content -->
            <main>
                     {{ $slot }}
            </main>

            <footer>
                <x-footer> </x-footer>
            </footer>
        </div>



         @stack('scripts')

         
         </body>



</html>


