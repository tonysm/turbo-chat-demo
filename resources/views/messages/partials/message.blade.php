<div
    data-user-id="{{ $message->creator_id }}"
    data-message-id="{{ $message->id }}"
    data-messages-target="message"
    class="w-3/4 my-1 p-4 rounded-md bg-gray-900 text-white message"
>
    <p class="whitespace-pre-wrap">{{ $message->id }} - {{ $message->content }}</p>
</div>
