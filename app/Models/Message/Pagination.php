<?php

namespace App\Models\Message;

use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;

trait Pagination
{
    const PAGE_SIZE = 10;

    public function scopeLastPage(Builder $query): void
    {
        $query->ordered('DESC')->take(static::PAGE_SIZE);
    }

    public function scopeFirstPage(Builder $query): void
    {
        $query->ordered('ASC')->take(static::PAGE_SIZE);
    }

    public function scopeBefore(Builder $query, Message $message): void
    {
        $query->where('created_at', '<', $message->created_at);
    }

    public function scopeAfter(Builder $query, Message $message): void
    {
        $query->where('created_at', '>', $message->created_at);
    }

    public function scopePageBefore(Builder $query, Message $message): void
    {
        $query->before($message)->lastPage();
    }

    public function scopePageAfter(Builder $query, Message $message): void
    {
        $query->after($message)->firstPage();
    }
}
