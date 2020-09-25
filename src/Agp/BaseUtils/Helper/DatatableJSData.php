<?php


namespace Agp\BaseUtils\Helper;


use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class DatatableJSData implements Arrayable, Jsonable, JsonSerializable
{

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray(), $options);
    }
}
