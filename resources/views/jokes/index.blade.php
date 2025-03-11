<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Jokes') }}
            </h2>
            <div class="flex space-x-4">
                @can(['joke.restore', 'joke.force-delete', 'joke.delete'])
                    <a href="{{ route('jokes.trashed') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-600">
                        {{ __('View Trash') }}
                    </a>
                @endcan
                @can('joke.add')
                    <a href="{{ route('jokes.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                        {{ __('Add Joke') }}
                    </a>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('jokes.index') }}" class="mb-6 space-y-4">
                        <div class="flex gap-4">
                            <x-text-input
                                id="search"
                                name="search"
                                type="text"
                                class="flex-1"
                                placeholder="Search jokes or tags..."
                                :value="$search"
                            />
                            <select name="category" class="rounded-md border-gray-300">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ $categoryId == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Jokes List -->
                    <div class="space-y-6">
                        @forelse($jokes as $joke)
                            <div class="bg-white shadow rounded-lg p-6 border">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1">
                                        <p class="text-lg text-gray-900">{{ $joke->joke }}</p>
                                        <div class="mt-2 text-sm text-gray-500">
                                            <p>
                                                Posted by {{ $joke->author->name }}
                                                in {{ $joke->category->name }}
                                                on {{ $joke->created_at->format('M d, Y') }}
                                            </p>
                                            @if($joke->tags)
                                                <p class="mt-1">
                                                    Tags: {{ $joke->tags }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                            <a href="{{ route('jokes.show', $joke) }}"
                                               class="text-blue-600 hover:underline">View</a>

                                        @if($joke->author_id === Auth::id() || auth()->user()->hasRole(['superuser', 'administrator', 'staff']))
                                            @can('joke.edit')
                                                <a href="{{ route('jokes.edit', $joke) }}"
                                                   class="text-yellow-600 hover:underline">Edit</a>
                                            @endcan
                                            @can('joke.delete')
                                                <form action="{{ route('jokes.destroy', $joke) }}"
                                                      method="POST"
                                                      class="inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this joke?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                </form>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 text-center py-4">No jokes found.</p>
                        @endforelse

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $jokes->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
