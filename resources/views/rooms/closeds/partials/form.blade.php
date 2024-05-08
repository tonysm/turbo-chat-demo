<form action="{{ $room->exists ? route('rooms.closeds.update', $room) : route('rooms.closeds.store') }}" method="post">
    @csrf

    @if ($room->exists)
        @method('PUT')
    @endif

    <div data-turbo-permanent id="room_name">
        <x-input-label :for="dom_id($room, 'name')" :value="__('Name')" />
        <x-text-input :id="dom_id($room, 'name')" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room?->name)" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mt-2 space-y-2">
        <h3 class="text-lg font-semibold">Users</h3>

        @foreach ($selectedUsers as $user)
            @include('rooms.closeds.partials.user', ['room' => $room, 'user' => $user, 'selected' => true])
        @endforeach

        <hr />

        @foreach ($unselectedUsers as $user)
            @include('rooms.closeds.partials.user', ['room' => $room, 'user' => $user, 'selected' => false])
        @endforeach
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ $room->exists ? route('rooms.opens.edit', $room) : route('rooms.opens.create') }}">Switch Type</a>

        <x-primary-button class="ms-3">
            {{ $room->exists ? __('Save') : __('Create') }}
        </x-primary-button>
    </div>
</form>
