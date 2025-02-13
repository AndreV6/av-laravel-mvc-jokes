<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Trashed Users') }}
            </h2>
            <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">
                Back to Users
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <table class="min-w-full">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left">Given Name</th>
                            <th class="px-6 py-3 text-left">Family Name</th>
                            <th class="px-6 py-3 text-left">Email</th>
                            <th class="px-6 py-3 text-left">Deleted At</th>
                            <th class="px-6 py-3 text-left">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($users as $user)
                            <tr>
                                <td class="px-6 py-4">{{ $user->given_name }}</td>
                                <td class="px-6 py-4">{{ $user->family_name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->deleted_at->format('Y-m-d H:i') }}</td>
                                <td class="px-6 py-4 space-x-2">
                                    <form action="{{ route('users.restore', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:underline">Restore</button>
                                    </form>
                                    <form action="{{ route('users.force-delete', $user->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure? This cannot be undone.')"
                                                class="text-red-600 hover:underline">Delete Permanently</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No users in trash.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
