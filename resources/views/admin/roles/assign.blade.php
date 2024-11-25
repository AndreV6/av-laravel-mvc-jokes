<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Roles & Permissions for') }} {{ $user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Roles Section -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <a href="{{ route('users.edit', $user) }}" class="text-blue-600 hover:underline">&larr; Back to User Edit</a>
                    </div>

                    <form method="POST" action="{{ route('admin.roles.update-user', $user) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Role Assignment') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">Select the roles to assign to this user. Role permissions are automatically granted.</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($roles as $role)
                                    <div class="relative flex items-start">
                                        <div class="flex h-6 items-center">
                                            <input type="checkbox"
                                                   id="role_{{ $role->id }}"
                                                   name="roles[]"
                                                   value="{{ $role->id }}"
                                                   class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                                   @if($role->name === 'superuser' && !Auth::user()->hasRole('superuser')) disabled @endif>
                                        </div>
                                        <div class="ml-3 text-sm leading-6">
                                            <label for="role_{{ $role->id }}" class="font-medium text-gray-900">{{ ucfirst($role->name) }}</label>
                                            <p class="text-gray-500">{{ $role->permissions->count() }} permissions</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Update Roles') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Permissions Section -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.permissions.update-user', $user) }}" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Direct Permissions') }}</h3>
                            <p class="text-sm text-gray-600 mb-4">Assign additional permissions directly to the user, independent of their roles.</p>

                            @foreach($groupedPermissions as $group => $permissions)
                                <div class="mb-8">
                                    <h4 class="text-base font-medium text-gray-900 mb-4 capitalize">{{ $group }} Permissions</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        @foreach($permissions as $permission)
                                            <div class="relative flex items-start">
                                                <div class="flex h-6 items-center">
                                                    <input type="checkbox"
                                                           id="permission_{{ $permission->id }}"
                                                           name="permissions[]"
                                                           value="{{ $permission->id }}"
                                                           class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-600"
                                                        {{ $user->hasDirectPermission($permission->name) ? 'checked' : '' }}>
                                                </div>
                                                <div class="ml-3 text-sm leading-6">
                                                    <label for="permission_{{ $permission->id }}" class="font-medium text-gray-900">
                                                        {{ ucfirst(explode('.', $permission->name)[1]) }}
                                                    </label>
                                                    <p class="text-gray-500">{{ $permission->name }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="flex justify-end">
                            <x-primary-button>{{ __('Update Permissions') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Current Effective Permissions -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('Effective Permissions') }}</h3>
                    <p class="text-sm text-gray-600 mb-4">All permissions this user has, including those granted through roles.</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($user->getAllPermissions() as $permission)
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <span class="text-sm font-medium text-gray-900">{{ $permission->name }}</span>
                                <p class="text-xs text-gray-500">via {{ $user->getDirectPermissions()->contains($permission) ? 'direct assignment' : 'role' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
