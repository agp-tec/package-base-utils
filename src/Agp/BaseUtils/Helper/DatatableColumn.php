<?php


namespace Agp\BaseUtils\Helper;


/**
 * Class DatatableColumn
 * Contem os atributos das colunas do KTDatatables
 * @package App\Helper
 */
class DatatableColumn
{
    public function set($key, $value)
    {
        $this->$key = $value;
        return $this;
    }

    public function get($key)
    {
        return $this->$key;
    }
}
