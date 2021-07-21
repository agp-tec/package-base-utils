<?php


namespace Agp\BaseUtils\Notifications;

use Agp\BaseUtils\Model\Service\PushService;
use Illuminate\Notifications\Notification;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $dados = $notification->toPush($notifiable);
        $pushService = new PushService();

        foreach ($dados['dispositivos'] as $d){
            $pushService->send($d, $dados['notificacao']);
        }
    }
}
