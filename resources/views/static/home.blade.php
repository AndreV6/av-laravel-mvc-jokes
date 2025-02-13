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
            <section class="grid grid-cols-1 gap-4 px-4 mt-4 sm:grid-cols-1 sm:px-8">

                <section class="rounded flex items-center bg-lime-200 border border-lime-600 overflow-hidden">
                    <div class="rounded-l p-6 bg-lime-600">
                        <i class=”fa-solid fa-exclamation”></i>
                    </div>
                    <div class="rounded-r px-6 text-lime-800">
                        <h3 class="tracking-wider">Total Members</h3>
                        <p class="text-3xl">{{ number_format($total_members) }}</p>
                    </div>
                </section>
                <section class="rounded flex items-center bg-lime-200 border border-lime-600 overflow-hidden">
                    <div class="rounded-l p-6 bg-lime-600">
                        <i class=”fa-solid fa-exclamation”></i>
                    </div>
                    <div class="rounded-r px-6 text-lime-800">
                        <h3 class="tracking-wider">Total Jokes</h3>
                        <p class="text-3xl">{{ number_format($total_jokes) }}</p>
                    </div>
                </section>

            </section>

            <section class="grid grid-cols-1 gap-4 px-4 mt-4 sm:grid-cols-1 sm:px-8">

                <article class=" bg-white shadow rounded p-2 flex flex-col">
                    <header class="-mx-2 bg-zinc-700 text-zinc-200 text-lg p-4 -mt-2 mb-4 rounded-t flex-0">
                        <h4>
                            Time for a Random Joke
                        </h4>
                    </header>
                    <section class="flex-grow flex flex-col space-y-3 text-zinc-600">
                        @if (!empty($random_joke->joke))
                            <p class="text-lg">{{ $random_joke->joke }}</p>
                        @else
                            no jokes
                        @endif
                    </section>
                    <footer class="-mx-2 bg-zinc-100 text-zinc-600 text-sm mt-4 -mb-2 rounded-b flex-0">
                        <p class="w-full text-right rounded-b hover:text-black px-4 py-2">
                            @if (!empty($random_joke->joke))
                                Posted by: {{ $random_joke->author->nickname ?? $random_joke->author->given_name . " " . $random_joke->author->family_name }}
                            @endif
                        </p>
                    </footer>
                    <button class="w-96 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded focus:outline-none
                               transition ease-in-out duration-500 mt-6" onclick="location.reload()">
                        New Joke
                    </button>
                </article>


            </section>


        </div>

    </article>

</x-guest-layout>
