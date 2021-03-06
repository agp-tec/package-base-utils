<?php

namespace Agp\BaseUtils\Traits;

use Agp\Log\Log;
use Agp\Login\Model\Service\UsuarioService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Tymon\JWTAuth\Facades\JWTAuth;

/**
 * Trait ValidUserRegistry
 * Verifica se usuário tem acesso ao objeto
 */
trait AuthenticateToken
{
    private function getUserId($payload)
    {
        if (!is_object($payload) || !method_exists($payload, 'getClaims'))
            return null;
        $claims = $payload->getClaims();
        if (!is_object($claims) || !method_exists($claims, 'get'))
            return null;
        $sub = $claims->get('sub');
        if (!is_object($sub) || !method_exists($sub, 'getValue'))
            return null;
        return $sub->getValue();
    }

    public function handle(Request $request, Closure $next)
    {
        //Rotas abertas
        if ($this->rotaAberta($request))
            return $next($request);

        $conta = null;
        //Rotas semi abertas
        if ($this->rotaSemiAberta($request)) {
            $token = request()->session()->get('token');
        } else {
            $data = (new UsuarioService())->getContas();
            if (count($data) <= 0)
                return redirect()->route("web.login.index");
            $conta = $data[0];
            if (!$conta)
                return redirect()->route("web.login.index");
            $token = $conta->token;
        }
        try {
            try {
                //Realiza a renovação do token
                JWTAuth::setToken($token);
                //Força expection de token expirado - Ao utilizar JWTAuth::refresh(), o sistema de autenticacao não valida a expiração do token automaticamente
                $payload = JWTAuth::payload();
                $userId = $conta->id ?? $this->getUserId($payload);
                //Valida dispositivo
                $dispositivo = UsuarioService::getDispositivoCookie($userId);
                if (!$dispositivo || !array_key_exists('id', $dispositivo))
                    return redirect()->to(
                        $userId ? URL::signedRoute("web.login.pass", ['user' => $userId]) : route("web.login.index")
                    )->with('error', 'Falha ao validar dispositivo. Acesse novamente.');
            } catch (\Exception $e) {
                //Salva infos da pagina acessada. Encaminha usuario apos login
                (new UsuarioService())->salvaDadosUrl($request);

                if ($conta->id ?? ($userId ?? null))
                    return redirect()->to(URL::signedRoute("web.login.pass", ['user' => $conta->id ?? $userId]))->with('error', 'Sessão expirada. Acesse novamente.');
                return redirect()->route("web.login.index")->with('error', 'Sessão expirada. Acesse novamente.');
            }
            if ($conta && (config('app.env') == 'production')) {
                $token = JWTAuth::refresh();
                JWTAuth::setToken($token);
                $data = [];
                $data[0] = $conta;
                $data[0]->token = $token;
                unset($data[0]->conectado);
                request()->session()->put(config('login.session_data'), json_encode($data));
            }
        } catch (\Exception $e) {
            Log::handleException($e);
            return redirect()->route("web.login.index");
        }

        $request->headers->set('Authorization', 'Bearer ' . $token);
        return $next($request);
    }
}
