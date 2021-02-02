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
trait AuthenticateContaIdToken
{
    public function handle(Request $request, Closure $next)
    {
        //Rotas abertas
        if ($this->rotaAberta($request))
            return $next($request);

        //Rotas semi abertas
        if ($this->rotaSemiAberta($request)) {
            $token = request()->session()->get('token');
            if ($token) {
                $request->headers->set('Authorization', 'Bearer ' . $token);
                return $next($request);
            }
        }

        $contaId = $request->contaId;
        if ($contaId == '')
            return redirect()->route("web.home", ['contaId' => '0']);

        $data = (new UsuarioService())->getContas();
        $count = count($data);
        if ($count <= 0)
            return redirect()->route("web.login.index");
        if ($contaId >= $count)
            return redirect()->route("web.home", ['contaId' => '0']);
        $conta = $data[$contaId];
        if (!$conta)
            return redirect()->route("web.login.index");

        try {
            $token = $data[$contaId]->token;
            try {
                //Realiza a renovação do token
                JWTAuth::setToken($token);
                //Força expection de token expirado - Ao utilizar JWTAuth::refresh(), o sistema de autenticacao não valida a expiração do token automaticamente
                JWTAuth::payload();
            } catch (\Exception $e) {
                //Salva infos da pagina acessada. Encaminha usuario apos login
                (new UsuarioService())->salvaDadosUrl($request, $contaId);

                if ($data[$contaId]->id ?? false)
                    return redirect()->to(URL::signedRoute("web.login.pass", ['user' => $data[$contaId]->id]))->with('error', 'Sessão expirada. Acesse novamente.');
                return redirect()->route("web.login.index")->with('error', 'Sessão expirada. Acesse novamente.');
            }
            if (config('app.env') == 'production') {
                $token = JWTAuth::refresh();
                JWTAuth::setToken($token);
                $data[$contaId]->token = $token;
                request()->session()->put(config('login.session_data'), json_encode($data));
            }
        } catch (\Exception $e) {
            Log::handleException($e);
            return redirect()->route("web.login.index");
        }

        $request->headers->set('Authorization', 'Bearer ' . $token);
        URL::defaults(['contaId' => $contaId]);
        $request->route()->forgetParameter('contaId');
        return $next($request);
    }
}
