<?php


namespace Agp\BaseUtils\Model\Entity;


class DatabaseNotification extends \Illuminate\Notifications\DatabaseNotification
{
    protected $connection = 'mysql-log';
    protected $table = 'log_notifications';

    public function __construct(array $attributes = [])
    {
        $this->setConnection(config('config.notification_connection'))
            ->setTable(config('config.notification_table'));

        parent::__construct($attributes);
    }
}
