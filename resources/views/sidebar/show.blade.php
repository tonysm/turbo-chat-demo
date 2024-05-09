<x-turbo::frame id="rooms_list" target="_top">
    @foreach ($memberships as $membership)
        @include('rooms.partials.room', ['room' => $membership->room])
    @endforeach
</x-turbo::frame>
