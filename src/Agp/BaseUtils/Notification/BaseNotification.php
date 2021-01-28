<?php


namespace Agp\BaseUtils\Notification;


use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification implements ShouldQueue
{
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $subtitle;
    /**
     * @var string
     */
    protected $icon;
    /**
     * @var string
     */
    protected $type;
    /**
     * @var string[]
     */
    protected $actions;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //Database notification
        $this->title = '';
        $this->subtitle = '';
        $this->icon = '';
        $this->type = '';
        $this->actions = [
            'link' => '#'
        ];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'icon' => $this->icon,
            'type' => $this->type,
            'actions' => $this->actions,
        ];
    }
}
