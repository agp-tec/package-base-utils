<?php

namespace Agp\BaseUtils\Model\Entity;

use Agp\BaseUtils\Traits\SyncRelations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BaseModel extends Model
{
    public $synchronized = false;

    use SyncRelations;

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function getMessages()
    {
        $res = [];
        $rules = $this->getRules();
        foreach ($rules as $key => $rule)
            $res[$key.'.*'] = 'O campo "' . Str::ucfirst(Str::camel($key)) . '" não é válido';

        return $res;
    }

    public function getRules()
    {
        return [];
    }
}
