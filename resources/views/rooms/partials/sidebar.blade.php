<div class="space-y-2" id="sidebar" data-turbo-permanent>
    <x-turbo::frame id="rooms_list" :src="route('sidebar.show')" target="_top" class="flex flex-col space-y-2">
    </x-turbo::frame>

    <a href="{{ route('rooms.opens.create') }}" class="inline-block px-4 py-2 rounded-full text-gray-200 border border-gray-200">{{ __('New Room') }}</a>
</div>
