<?php

namespace Agp\BaseUtils\Traits;

/**
 * Trait ValidUserRegistry
 * Verifica se usuário tem acesso ao objeto
 */
trait ValidUserRegistry
{
    public function validUserRegistry()
    {
        if (auth()->check())
            return $this->adm_empresa_id == auth()->user()->getAdmEmpresaId();
        return true;
    }
}
