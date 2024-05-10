<form action="{{ $room->exists ? route('rooms.opens.update', $room) : route('rooms.opens.store') }}" method="post">
    @csrf
    @if ($room->exists)
        @method('PUT')
    @endif

    <div data-turbo-permanent id="room_name">
        <x-input-label :for="dom_id($room, 'name')" :value="__('Name')" />
        <x-text-input :id="dom_id($room, 'name')" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room?->name)" required autocomplete="off" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mt-2">
        <h3 class="text-lg font-semibold">Access</h3>

        <a data-turbo-action="replace" href="{{ $room->exists ? route('rooms.closeds.edit', $room) : route('rooms.closeds.create') }}">
            <x-toggle>
                <x-slot name="input">
                    <input class="sr-only" type="checkbox" checked="checked" />
                </x-slot>

                {{ __('Everyone Has Access') }}
            </x-toggle>
        </a>
    </div>

    <div class="mt-2 space-y-2">
        <h3 class="text-lg font-semibold">Users</h3>

        @foreach ($users as $user)
            <div class="px-1 flex items-center space-x-2 justify-between font-medium">
                <span class="flex-1 dark:text-gray-200">{{ $user->name }}</span>

                <span class="w-10 !h-[42.5px] text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </span>
            </div>
        @endforeach
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button class="ms-3">
            {{ $room->exists ? __('Save') : __('Create') }}
        </x-primary-button>
    </div>
</form>
