<?php

namespace Agp\BaseUtils\Traits;

use Illuminate\Notifications\RoutesNotifications;

trait Notifiable
{
    use HasDatabaseNotifications, RoutesNotifications;
}
