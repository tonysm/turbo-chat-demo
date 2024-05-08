<form action="{{ route('rooms.closeds.store') }}" method="post">
    @csrf

    <div data-turbo-permanent id="room_name">
        <x-input-label :for="dom_id($room, 'name')" :value="__('Name')" />
        <x-text-input :id="dom_id($room, 'name')" class="block mt-1 w-full" type="text" name="name" :value="old('name', $room?->name)" required autofocus autocomplete="username" />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div class="mt-2 space-y-2">
        <h3 class="text-lg font-semibold">Users</h3>

        @foreach ($users as $user)
        <div>
            <label class="flex items-center space-x-1">
                @if (auth()->user()->is($user))
                    <input type="hidden" hidden name="users[]" value="{{ $user->id }}" />
                    <input type="checkbox" name="users[]" checked="checked" disabled value="{{ $user->id }}" />
                @else
                    <input type="checkbox" name="users[]" value="{{ $user->id }}" />
                @endif
                <span>{{ $user->name }}</span>
            </label>
        </div>
        @endforeach
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('rooms.opens.create') }}">Switch Type</a>

        <x-primary-button class="ms-3">
            {{ __('Create') }}
        </x-primary-button>
    </div>
</form>
