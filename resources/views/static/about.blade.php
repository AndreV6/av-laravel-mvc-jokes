<x-guest-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Andre's {{ __('Joke DB') }}
        </h2>
    </x-slot>


    <article class="-mx-4">
        <header class="bg-zinc-700 text-zinc-200 rounded-t-lg -mx-4 -mt-8 p-8 text-2xl font-bold mb-8">
            <h2>Welcome</h2>
        </header>

        <div class="flex flex-col flex-wrap my-4 mt-8">
            <div class="flex items-center justify-between p-4 rounded-l-lg">Author: Andre Velevski <20094240@tafe.wa.edu.au></div>
            <div class="flex items-center justify-between p-4 rounded-l-lg">this application is an application that uses laravel framework to store jokes and tell jokes. jokes can be managed by users</div>
            <div class="flex items-center justify-between p-4 rounded-l-lg">languages used are css tailwind, html, JavaScript, and php</div>
            <div class="flex items-center justify-between p-4 rounded-l-lg">laragon, Vite, apache, mailpit, sqlite, nodejs are used to test the mvc. mvc is coded on phpstorm</div>
        </div>

    </article>

</x-guest-layout>
