<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add New Joke') }}
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

                    <form method="POST" action="{{ route('jokes.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <x-input-label for="joke" :value="__('Your Joke')" />
                            <textarea
                                id="joke"
                                name="joke"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                rows="3"
                                required
                            >{{ old('joke') }}</textarea>
                            <x-input-error class="mt-2" :messages="$errors->get('joke')" />
                        </div>

                        <div>
                            <x-input-label for="category_id" :value="__('Category')" />
                            <select
                                id="category_id"
                                name="category_id"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                                required
                            >
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-input-error class="mt-2" :messages="$errors->get('category_id')" />
                        </div>

                        <div>
                            <x-input-label for="tags" :value="__('Tags (comma separated)')" />
                            <x-text-input
                                id="tags"
                                name="tags"
                                type="text"
                                class="mt-1 block w-full"
                                :value="old('tags')"
                                placeholder="funny, pun, programming"
                            />
                            <x-input-error class="mt-2" :messages="$errors->get('tags')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Create Joke') }}</x-primary-button>
                            <a href="{{ route('jokes.index') }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
