<?php


namespace Agp\BaseUtils\Model\Repository;


use Agp\BaseUtils\Helper\Datatable;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BaseRepository
{
    public $hasAdmEmpresa;
    protected $resourceClassName;

    public function getList()
    {
        return $this->className::all();
    }

    public function getById($id)
    {
        return $this->className::query()->findOrFail($id);
    }

    public function datatableData($builder = null)
    {
        $query = request()->get('query');
        //TODO Como pesquisar por dado sensivel?

        if (!$builder) {
            $builder = $this->className::query();
            if ($this->hasAdmEmpresa)
                $builder = $builder->where(['adm_empresa_id' => auth()->user()->getAdmEmpresaId()]);
        }
        if (isset($query)) {
            foreach ($query as $key => $value) {
                if (str_contains($key, 'generalSearch')) {
                    if (method_exists($this, 'procuraGenerica')) {
                        //Se contém generalSearch, realiza procura generica e ignora procuras especificas do campo
                        $list = $this->procuraGenerica($value);
                        if ($list instanceof LengthAwarePaginator) {
                            $list = Datatable::convertPaginationLaravelToKTDatatables($list);
                            if ($this->resourceClassName)
                                $list->data = $this->resourceClassName::collection($list->data);
                        } elseif ($this->resourceClassName)
                            $list = $this->resourceClassName::collection($list);
                        return $list;
                    } else
                        throw new \Exception('Repositório sem o método de procura genérica.');
                }
            }
            //Monta where de filtro
            $model = new $this->className(); //Instancia pra pegar fillable
            foreach ($query as $key => $value) {
                if (in_array($key, $model->getFillable()))
                    $builder = $builder->where($key, 'like', '%' . $value . '%');
            }
        }
        //TODO Descobrir como ordenar colunas de tabelas associadas
        if (request()->get('sort'))
            $builder = $builder->orderBy(request()->get('sort')['field'], request()->get('sort')['sort']);
        $list = $this->executa($builder);
        if ($list instanceof LengthAwarePaginator) {
            $list = Datatable::convertPaginationLaravelToKTDatatables($list);
            if ($this->resourceClassName)
                $list->data = $this->resourceClassName::collection($list->data);
        } elseif ($this->resourceClassName)
            $list = $this->resourceClassName::collection($list);
        return $list;
    }

    protected function executa(Builder $builder)
    {
        //Se possui no request parametros de paginacao, executa paginate()
        if (request()->get('per_page') && (request()->get('per_page') > 0))
            return $builder->paginate(request()->per_page, ['*'], 'page', request()->page);
        else
            return $builder->get();
    }
}
