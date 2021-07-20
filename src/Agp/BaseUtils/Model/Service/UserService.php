<?php

namespace Agp\BaseUtils\Model\Service;


use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;

class UserService
{

    public static function getAplicativo()
    {

        $idAplicativo = config('config.id_app');
        //return (string) $idAplicativo;

        $uri = config('config.api_agpadmin') . '/public/aplicativo/' .$idAplicativo;
        //dd($uri, config('config.api_client_token'));
        $response = Http::withHeaders([
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
            'client-token' => config('config.api_client_token'),
        ])->get($uri);

        if (($response->status() >= 200) && ($response->status() < 300)){
           return (object)((object) $response->json())->data;
        }

        return null;
    }
}
