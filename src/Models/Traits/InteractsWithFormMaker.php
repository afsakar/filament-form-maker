<?php

namespace Afsakar\FormMaker\Models\Traits;

use Illuminate\Notifications\Notification;

trait InteractsWithFormMaker
{
    public static function notification(Notification $notification, array $extraUsers = [])
    {
        $users = self::whereIn('id', $extraUsers ?: [])
            ->get();

        return $users->each->notify($notification);
    }
}
