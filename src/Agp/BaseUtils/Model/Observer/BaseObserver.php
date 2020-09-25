<?php


namespace Agp\BaseUtils\Model\Observer;


use Agp\Log\Jobs\LogJob;
use Agp\Log\Log;

class BaseObserver
{
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

    public function created($obj)
    {
        LogJob::dispatch(new Log(1, $this->nome . ' ' . $obj->{$this->campo} . ' cadastrad' . $this->genero, $obj->getTable()));
    }

    public function updated($obj)
    {
        LogJob::dispatch(new Log(2, $this->nome . ' ' . $obj->{$this->campo} . ' atualizad' . $this->genero, $obj->getTable()));
    }

    public function deleting($obj)
    {
        LogJob::dispatch(new Log(3, $this->nome . ' ' . $obj->{$this->campo} . ' removid' . $this->genero, $obj->getTable()));
    }
}
