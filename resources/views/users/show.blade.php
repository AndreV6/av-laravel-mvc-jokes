<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('users.index') }}"
                           class="text-blue-600 hover:underline">&larr; Back to Users</a>
                    </div>

                    <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Nickname</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->nickname ?? '-' }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Email Verified</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">Verified on {{ $user->email_verified_at->format('Y-m-d H:i') }}</span>
                                @else
                                    <span class="text-red-600">Not verified</span>
                                @endif
                            </dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Creator</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->creator ? $user->creator->name : 'System' }}</dd>
                        </div>

                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Registered Date</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('users.edit', $user) }}"
                           class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-600">
                            Edit User
                        </a>
                        <form action="{{ route('users.destroy', $user) }}"
                              method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this user?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600">
                                Delete User
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
