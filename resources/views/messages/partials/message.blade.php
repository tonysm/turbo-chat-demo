<div
    data-user-id="{{ $message->creator_id }}"
    data-messages-target="message"
    class="w-3/4 my-1 p-4 rounded-md bg-gray-900 text-white"
>
    <p class="whitespace-pre-wrap">{{ $message->content }}</p>
</div>
