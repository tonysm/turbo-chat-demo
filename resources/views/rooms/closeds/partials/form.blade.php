<form action="{{ $room->exists ? route('rooms.closeds.update', $room) : route('rooms.closeds.store') }}" method="post">
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

        <a data-turbo-action="replace" href="{{ $room->exists ? route('rooms.opens.edit', $room) : route('rooms.opens.create') }}">
            <x-toggle>
                <x-slot name="input">
                    <input class="sr-only" type="checkbox" />
                </x-slot>

                {{ __('Everyone Has Access') }}
            </x-toggle>
        </a>
    </div>

    <div class="mt-2 space-y-2">
        <h3 class="text-lg font-semibold">Users</h3>

        @foreach ($users as $user)
            @include('rooms.closeds.partials.user', ['room' => $room, 'user' => $user, 'selected' => $selectedUsersIds->contains($user->id)])
        @endforeach
    </div>

    <div class="flex items-center justify-end mt-4">
        <x-primary-button class="ms-3">
            {{ $room->exists ? __('Save') : __('Create') }}
        </x-primary-button>
    </div>
</form>
