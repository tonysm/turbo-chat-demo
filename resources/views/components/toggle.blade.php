<div class="p-1 flex items-center justify-center">
    <label {{ $attributes->merge(['role' => 'switch', 'class' => 'w-full group flex cursor-pointer has-[input:disabled]:cursor-not-allowed items-center justify-between space-x-2 font-medium text-gray-900']) }}>
        <div class="flex-1 dark:text-gray-200">{{ $slot }}</div>

        <div>
            @isset ($input)
            {{ $input }}
            @else
            <input type="checkbox" {{ $attributes }} class="peer sr-only" />
            @endisset

            <div class="relative ml-4 inline-flex w-14 rounded-full bg-slate-300 py-1 transition group-has-[input:checked]:bg-indigo-400">
                <span class="h-6 w-6 translate-x-1 rounded-full bg-white shadow-md transition group-has-[input:checked]:translate-x-7" aria-hidden="true"></span>
            </div>
        </div>
    </label>
</div>
