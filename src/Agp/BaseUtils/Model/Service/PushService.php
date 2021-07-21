<?php

namespace Agp\BaseUtils\Model\Service;


use Agp\Login\Model\Service\UsuarioService;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;

class PushService
{
    public function createSubscription($usuarioDispositivo, $dados)
    {

        $uri = config('config.api_agpadmin') . "/usuario-dispositivo/$usuarioDispositivo->id/set-subscricao";
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'Authorization' => 'bearer ' . auth()->getToken(),
        ])->patch($uri, $dados);

        if (($response->status() >= 200) && ($response->status() < 300)){
            return $response->json();
        }
        throw ValidationException::withMessages(['message' => 'Não foi possível completar ação. Tente novamente mais tarde.']);
    }

    public function send($dispositivo, $message)
    {
        // Caso seja modificado as chaves, é preciso trocar no arquivo JS.
        $auth = [
            'VAPID' => [
                'subject' => 'mailto:contato@agp.com',
                'publicKey' => 'BFpwqItvvo0aQV9aoGR87z1ONMOSx33-huHS9F9_dzlqXy6d9yyQ30iCVBBZHRN5dz6xPooQQQhrdsm3PLiTllI',
                'privateKey' => 'TbzPuhJm0WHooyaaGttbZaMmKHSHXkEJbJ6a6WsfGM4',
            ],
        ];
        $webPush = new WebPush($auth);
        $subscription = json_decode($dispositivo->subscricao, true);
        $report = $webPush->sendOneNotification(
            Subscription::create($subscription),
            $message
        );

        if(!$report->isSuccess()){
            $this->createSubscription($dispositivo, ['subscricao' => null]);
        }
    }
}
