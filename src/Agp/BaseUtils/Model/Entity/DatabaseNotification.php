<?php


namespace Agp\BaseUtils\Model\Entity;


class DatabaseNotification extends \Illuminate\Notifications\DatabaseNotification
{
    protected $table = 'log_notifications';
}
