<?php


namespace Agp\BaseUtils\Controller\Web;


use Agp\BaseUtils\Model\Service\PushService;
use Agp\Login\Model\Service\UsuarioService;
use Illuminate\Http\Request;

class PushController
{
    public function subscription()
    {
        $usuarioDispositivo = (object) UsuarioService::getDispositivoCookie();
        (new PushService())->createSubscription($usuarioDispositivo, request()->all());

        return '';
    }
}
