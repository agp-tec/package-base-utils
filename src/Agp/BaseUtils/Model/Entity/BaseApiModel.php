<?php

namespace Agp\BaseUtils\Model\Entity;

use Agp\BaseUtils\Traits\SyncRelations;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class BaseApiModel extends BaseModel
{
    protected $app = null;
    protected $endpoint = null;
    protected $authorization = null;
    protected $client_token = null;
    /** Retorna resource da entidade
     * @var Closure
     */
    protected $resourceClosure = null;

    private function prepare()
    {
        if ($this->app == null)
            throw new \Exception('Variável app não inicializada');
        if ($this->endpoint == null)
            throw new \Exception('Variável endpoint não inicializada');
        if ($this->authorization == null && $this->client_token == null)
            throw new \Exception('Variável token não inicializada');
        if ($this->resourceClass == null)
            throw new \Exception('Variável resource não inicializada');
    }

    public function push()
    {
        $this->save();
    }

    public function save(array $options = [])
    {
        $this->prepare();

        $headers = [
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
        ];
        if ($this->authorization)
            $headers['Authorization'] = $this->authorization;
        if ($this->client_token)
            $headers['client-token'] = $this->client_token;

        $resourceClass = $this->resourceClass;
        $body = $resourceClass($this);
        if ($this->exists)
            $response = Http::withHeaders($headers)->put($this->endpoint . '/' . $this->getKey(), $body);
        else
            $response = Http::withHeaders($headers)->post($this->endpoint, $body);
        return (($response->status() >= 200) && ($response->status() <= 299));
    }

    public function delete()
    {
        $this->prepare();

        $headers = [
            'Content-type' => 'application/json',
            'Accept' => 'application/json',
        ];
        if ($this->authorization)
            $headers['Authorization'] = $this->authorization;
        if ($this->client_token)
            $headers['client-token'] = $this->client_token;

        if ($this->exists) {
            $response = Http::withHeaders($headers)->delete($this->endpoint . '/' . $this->getKey());
            return (($response->status() >= 200) && ($response->status() <= 299));
        }
        return false;
    }
}
