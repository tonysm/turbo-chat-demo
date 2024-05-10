<div>
    @if (auth()->user()->is($user))
    <div class="px-1 flex items-center space-x-2 justify-between font-medium">
        <span class="flex-1 dark:text-gray-200">{{ $user->name }} <input type="hidden" name="users[]" checked value="{{ $user->id }}" /></span>

        <span class="w-10 !h-[42.5px] text-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
            </svg>
        </span>
    </div>
    @else
    <x-toggle>
        <x-slot name="input">
            <input class="sr-only" type="checkbox" name="users[]" @if ($selected ?? false) checked @endif value="{{ $user->id }}" />
        </x-slot>

        {{ $user->name }}
    </x-toggle>
    @endif
</div>
