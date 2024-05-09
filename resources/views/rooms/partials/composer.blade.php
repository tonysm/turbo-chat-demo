<x-turbo::frame :id="[$room, 'create_message']" class="block" data-controller="composer" data-action="turbo:submit-end->composer#reset">
    <form action="{{ route('rooms.messages.store', $room) }}" method="POST" data-composer-target="form">
        @csrf
        <div>
            <label for="{{ dom_id($room, 'content_field') }}" class="sr-only">Message</label>
            <textarea
                data-composer-target="messageBox"
                data-action="keydown.enter->composer#submitByKeyboard"
                name="content"
                id="{{ dom_id($room, 'content_field') }}"
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            ></textarea>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ms-3">
                {{ __('Send!') }}
            </x-primary-button>
        </div>
    </form>
</x-turbo::frame>
