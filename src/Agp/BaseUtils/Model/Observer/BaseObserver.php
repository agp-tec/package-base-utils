<?php


namespace Agp\BaseUtils\Model\Observer;


use Agp\Log\Jobs\LogJob;
use Agp\Log\Log;
use Agp\Webhook\Model\Repository\WebhookRepository;

class BaseObserver
{
    /** Nome da model. Utilizado para criacao de nome de evento dinamico
     * @var string
     */
    protected $modelName = '';
    /** Full Classname para disparo de webhook
     * @var string
     */
    protected $webhookClass = '';
    /** Array de eventos para disparto de webhook. Se null, pega o nome do metodo do observer (created, updated, deleted, etc)
     * @var string
     */
    protected $webhookEvents = null;
    /** Membro de Entidade a ser mostrado: Ex. Pessoa cadastrada
     * @var string
     */
    protected $nome = 'Registro';
    /** Campo descricao a ser mostrado: Ex. Pessoa $obj->campo cadastrado
     * @var string
     */
    protected $campo = 'id';
    /** Vogal final do registro de log
     * @var string
     */
    protected $genero = 'o';

    private function verifyWebhook($obj, $funcName)
    {
        if (!class_exists($this->webhookClass))
            return;
        $events = $this->webhookEvents ? $this->webhookEvents : ['ev_' . $this->modelName . '_' . $funcName];
        $webhooks = WebhookRepository::getByEventos($events, $obj->adm_empresa_id);
        foreach ($webhooks as $webhook)
            $this->webhookClass::dispatch($funcName == 'deleted' ? ['id' => $obj->getKey()] : $obj, $webhook->getKey(), $events);
    }

    public function created($obj)
    {
        LogJob::dispatch(new Log(1, $this->nome . ' ' . $obj->{$this->campo} . ' cadastrad' . $this->genero, $obj->getTable()));
        $this->verifyWebhook($obj, __FUNCTION__);
    }

    public function updated($obj)
    {
        LogJob::dispatch(new Log(2, $this->nome . ' ' . $obj->{$this->campo} . ' atualizad' . $this->genero, $obj->getTable()));
        $this->verifyWebhook($obj, __FUNCTION__);
    }

    public function deleting($obj)
    {
        LogJob::dispatch(new Log(3, $this->nome . ' ' . $obj->{$this->campo} . ' removid' . $this->genero, $obj->getTable()));
    }

    public function deleted($obj)
    {
        $this->verifyWebhook($obj, __FUNCTION__);
    }
}
