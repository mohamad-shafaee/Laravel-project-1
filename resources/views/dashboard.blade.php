<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div @if (app()->getLocale() =='fa') class="rtl" @else class="rtl" @endif >
                    
                   @if (app()->getLocale() =='fa') Right @else Left @endif
                    You're logged in bb!
                </div>
            </div>
        </div>
    </div>

    <x-slot name="footer">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Footer') }}
        </h2>
    </x-slot>
</x-app-layout>
