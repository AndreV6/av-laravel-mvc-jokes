<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Users') }}
            </h2>
            <a href="{{ route('users.create') }}"
               class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                {{ __('Add User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('users.index') }}" class="mb-6">
                        <div class="flex gap-4">
                            <x-text-input
                                id="search"
                                name="search"
                                type="text"
                                class="flex-1"
                                placeholder="Search users..."
                                :value="$search"
                            />
                            <x-primary-button type="submit">
                                {{ __('Search') }}
                            </x-primary-button>
                        </div>
                    </form>

                    <!-- Users Table -->
                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Nickname</th>
                                <th scope="col" class="px-6 py-3">Email</th>
                                <th scope="col" class="px-6 py-3">Verified</th>
                                <th scope="col" class="px-6 py-3">Registered</th>
                                <th scope="col" class="px-6 py-3">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->nickname ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                        @if($user->email_verified_at)
                                            <td class="px-6 py-4">{{ $user->email_verified_at->format('Y-m-d') }}</td>
                                        @else
                                            <span class="text-red-600">Not Verified</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('users.show', $user) }}"
                                           class="text-blue-600 hover:underline">View</a>
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-yellow-600 hover:underline">Edit</a>
                                        <form action="{{ route('users.destroy', $user) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to delete this users?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
