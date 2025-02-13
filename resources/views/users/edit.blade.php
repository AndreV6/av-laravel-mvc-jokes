<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('users.index') }}" class="text-blue-600 hover:underline">&larr; Back to Users</a>
                    </div>

                    <!-- Regular User Edit Form -->
                    <form method="POST" action="{{ route('users.update', $user) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="given_name" :value="__('Given Name')" />
                            <x-text-input id="given_name" name="given_name" type="text" class="mt-1 block w-full" :value="old('given_name', $user->given_name)" required autofocus />
                            <x-input-error class="mt-2" :messages="$errors->get('given_name')" />
                        </div>

                        <div>
                            <x-input-label for="family_name" :value="__('Family Name')" />
                            <x-text-input id="family_name" name="family_name" type="text" class="mt-1 block w-full" :value="old('family_name', $user->family_name)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('family_name')" />
                        </div>

                        <div>
                            <x-input-label for="nickname" :value="__('Nickname (Optional)')" />
                            <x-text-input id="nickname" name="nickname" type="text" class="mt-1 block w-full" :value="old('nickname', $user->nickname)" />
                            <x-input-error class="mt-2" :messages="$errors->get('nickname')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>
                            <a href="{{ route('users.show', $user) }}"
                               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                {{ __('Cancel') }}
                            </a>

                            @if(Auth::user()->hasRole(['superuser', 'administrator']))
                                <a href="{{ route('admin.roles.assign', $user) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">
                                    {{ __('Manage Roles & Permissions') }}
                                </a>
                            @endif
                        </div>
                    </form>

                    @if(Auth::user()->hasRole(['superuser', 'administrator']))
                        <div class="mt-10 pt-8 border-t">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Current Roles & Permissions') }}</h3>

                            <!-- Display Current Roles -->
                            <div class="mb-6">
                                <h4 class="text-md font-medium text-gray-700 mb-2">Roles:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->roles as $role)
                                        <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                    @empty
                                        <span class="text-gray-500 italic">No roles assigned</span>
                                    @endforelse
                                </div>
                            </div>

                            <!-- Display Current Permissions -->
                            <div>
                                <h4 class="text-md font-medium text-gray-700 mb-2">Direct Permissions:</h4>
                                <div class="flex flex-wrap gap-2">
                                    @forelse($user->getDirectPermissions() as $permission)
                                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        {{ ucfirst($permission->name) }}
                                    </span>
                                    @empty
                                        <span class="text-gray-500 italic">No direct permissions assigned</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
