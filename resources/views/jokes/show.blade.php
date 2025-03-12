<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Joke Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('jokes.index') }}"
                           class="text-blue-600 hover:underline">&larr; Back to Jokes</a>
                    </div>

                    <div class="bg-white rounded-lg p-6 border">
                        <!-- Joke Content -->
                        <div class="prose max-w-none">
                            <p class="text-xl text-gray-900">{{ $joke->joke }}</p>
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <span class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-sm font-medium text-blue-800">
                                {{ $joke->category->name }}
                            </span>
                        </div>

                        <!-- Tags -->
                        @if($joke->tags)
                            <div class="mt-4">
                                <h3 class="text-sm font-medium text-gray-500">Tags:</h3>
                                <div class="mt-1 flex flex-wrap gap-2">
                                    @foreach(explode(',', $joke->tags) as $tag)
                                        <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-sm font-medium text-gray-800">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Meta Information -->
                        <div class="mt-6 text-sm text-gray-500 space-y-1">
                            <p>Posted by: {{ $joke->author->nickname ?? $joke->author->given_name . " " . $joke->author->family_name}}</p>
                            <p>Category: {{ $joke->category->name }}</p>
                            <p>Created: {{ $joke->created_at->format('F j, Y g:i A') }}</p>
                            @if($joke->updated_at && $joke->updated_at->ne($joke->created_at))
                                <p>Last updated: {{ $joke->updated_at->format('F j, Y g:i A') }}</p>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        @if(auth()->check() && (auth()->user()->hasRole(['superuser', 'administrator', 'staff']) || $joke->author_id === Auth::id()))
                            <div class="mt-6 flex gap-4">
                                <a href="{{ route('jokes.edit', $joke) }}"
                                   class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                                    Edit Joke
                                </a>

                                <form action="{{ route('jokes.destroy', $joke) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this joke?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600">
                                        Delete Joke
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- Navigation -->
                        <div class="mt-8 border-t pt-6 flex justify-between">
                            <div>
                            </div>
                            <a href="{{ route('jokes.index', ['category' => $joke->category_id]) }}"
                               class="text-blue-600 hover:underline">
                                More jokes in {{ $joke->category->name }} &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
