<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Trashed Jokes') }}
            </h2>
            <a href="{{ route('jokes.index') }}" class="text-blue-600 hover:underline">
                Back to Jokes
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @forelse($jokes as $joke)
                        <div class="mb-4 p-4 border rounded">
                            <p class="text-lg">{{ $joke->joke }}</p>
                            <p class="text-sm text-gray-600">By: {{ $joke->author->name }}</p>
                            <p class="text-sm text-gray-600">Deleted: {{ $joke->deleted_at->format('Y-m-d H:i') }}</p>
                            <div class="mt-2 space-x-2">
                                <form action="{{ route('jokes.restore', $joke->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-green-600 hover:underline">Restore</button>
                                </form>
                                <form action="{{ route('jokes.force-delete', $joke->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure? This cannot be undone.')"
                                            class="text-red-600 hover:underline">Delete Permanently</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p>No jokes in trash.</p>
                    @endforelse

                    {{ $jokes->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
