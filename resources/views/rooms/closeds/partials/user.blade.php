<div>
    <label class="flex items-center space-x-1">
        @if (auth()->user()->is($user) && ! $room->exists)
            <input type="hidden" hidden name="users[]" value="{{ $user->id }}" />
            <input type="checkbox" name="users[]" checked="checked" disabled value="{{ $user->id }}" />
        @else
            <input type="checkbox" name="users[]" @if ($selected ?? false) checked @endif value="{{ $user->id }}" />
        @endif
        <span>{{ $user->name }}</span>
    </label>
</div>
