<x-app-layout>
    <x-slot name="meta">
        <x-turbo::refreshes-with method="morph" scroll="preserve" />
    </x-slot>

    <x-slot name="header">
        <div class="flex items-center space-x-2">
            <h2 class="inline-block font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight" style="view-transition-name: room-title;">
                {{ $room->name }}
            </h2>

            <a class="text-gray-200" href="{{ $room->isClosed() ? route('rooms.closeds.edit', $room) : route('rooms.opens.edit', $room) }}">{{ __('edit') }}</a>
        </div>
    </x-slot>

    <div class="py-12 flex space-x-4 justify-center">
        <div class="max-w-2xl w-full sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 space-y-2">
                    <div
                        id="{{ dom_id($room, 'messages') }}"
                        class="h-[50vh] overflow-y-auto flex flex-col-reverse"
                        data-controller="messages"
                        data-messages-user-id-value="{{ auth()->id() }}"
                        data-messages-page-url-value="{{ route('rooms.messages.index', $room) }}"
                        data-messages-me-class="ml-auto mr-0"
                        style="view-transition-name: message-box;"
                    >
                        @foreach ($messages as $message)
                            @include('messages.partials.message', ['message' => $message])
                        @endforeach
                    </div>

                    @include('rooms.partials.composer', ['room' => $room])
                </div>
            </div>
        </div>

        <div class="w-60">
            @include('rooms.partials.sidebar')
        </div>
    </div>
</x-app-layout>
