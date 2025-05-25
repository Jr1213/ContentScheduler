<?php

namespace App\Filament\Widgets;

use App\Enums\PostStatusEnum;
use App\Models\Post;
use Filament\Widgets\Widget;
use Saade\FilamentFullCalendar\Widgets\FullCalendarWidget;

class ScheduledPostsCalendar extends FullCalendarWidget
{
    // protected static string $view = 'filament.widgets.scheduled-posts-calendar';

    //  protected int | string | array $columnSpan = 'full';
    public function fetchEvents(array $fetchInfo): array
    {
        return Post::whereUserId(request()->user()->id)->whereStatus(PostStatusEnum::SCHEDULED)->get()
            ->map(function (Post $post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'start' => $post->scheduled_time,
                    'end' => $post->scheduled_time,
                    'url' => route('filament./.resources.posts.edit', $post),
                ];
            })->toArray();
    }
}
