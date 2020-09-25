<?php


namespace Agp\BaseUtils\Helper;

use Illuminate\Support\Collection;

/**
 * Class Datatable
 * Retorna uma notação json no formato dos parametros utilizados pelo JS var datatable = $('#datatable').KTDatatable(params);
 * É preciso adicionar os métodos que são função manualmente na view datatable.
 *
 * Criar/adicionar para cada entidade:
 * Create ViewComposer/PessoaComposer.php
 * Add config/app.php => Aliases
 * Add routes/web.php => Route::get('pessoa-data', 'PessoaController@datatable')->name('pessoa.datatable');
 * Add Controller/Web/PessoaController@datatable
 * Add Model/Repository/PessoaRepository => public function procuraGenerica($expressao)
 * Create view/pessoa/datatable.blade.php
 * Create view/pessoa/actions.blade.php
 * Add view/pessoa/index.blade.php => {{ PessoaComposer::getDatatables(); }}
 *
 * Implementar na view:
 * generalSearch: Adicionar imput com o id="generalSearch_{{$datatable->id}}"
 * fieldSearch: Adicionar o imput e o evento on('change' no JS da view datatable
 * innerHtml: Utilizar datatable.getColumnByField('nome').template = function(row, index, datatable) { return '<p>..</p>' }; no JS da view datatable
 *
 * @package App\Helper
 */
class Datatable
{
    /**
     * @var
     */
    public $data;
    /**
     * @var
     */
    public $layout;
    /**
     * @var
     */
    public $search;
    /**
     * @var Collection
     */
    public $columns;
    /**
     * @var
     */
    public $translate;
    /** Define se datatable é renderizada como modal
     * @var
     */
    public $isModal;

    /** Titulo da form de modal
     * @var
     */
    public $title;

    /** Descricao da form de modal
     * @var
     */
    public $subtititle;

    /** ID do div da datatable
     * @var string
     */
    public $id;

    /**
     * Datatable constructor.
     * @param string $id ID do div da datatable
     * @param bool $isModal Define se datatable será mostrada em um modal
     */
    public function __construct($id, $isModal = false)
    {
        $this->id = $id;
        $this->isModal = $isModal;
        $this->init();
        $this->setAjaxUrl('');
        $this->setAjaxUrlMethod('GET');
    }

    /**
     * Cria os objetos stdClass para ser traduzido em notação json posteriormente
     */
    private function init()
    {
        $this->instanciaObj($this->data);
        $this->instanciaObj($this->data->source);
        $this->instanciaObj($this->data->source->read);
        $this->instanciaObj($this->layout);
        $this->instanciaObj($this->search);
        $this->instanciaObj($this->toolbar);
        $this->instanciaObj($this->toolbar->items);
        $this->instanciaObj($this->toolbar->items->pagination);
        $this->instanciaObj($this->translate);
        $this->instanciaObj($this->translate->records);
        $this->instanciaObj($this->translate->toolbar);
        $this->instanciaObj($this->translate->toolbar->pagination);
        $this->instanciaObj($this->translate->toolbar->pagination->items);
        $this->instanciaObj($this->translate->toolbar->pagination->items->default);
        $this->columns = new Collection();

        //Valores default do objeto
        $this->data->type = 'remote';
        $this->data->pageSize = 10;
        $this->data->serverPaging = true;
        $this->data->serverFiltering = true;
        $this->data->serverSorting = true;
        $this->layout->scroll = true;
        $this->layout->footer = false;
        $this->sortable = true;
        $this->pagination = true;
        $this->search->key = 'generalSearch_' . $this->id;
        $this->search->delay = 750;

        //Paginacao
        $this->toolbar->items->pagination->pageSizeSelect = [5, 10, 20, 30, 50, 100, 250, 500, 1000, -1];

        //Tradução
        $this->translate->records->processing = 'Carregando...';
        $this->translate->records->noRecords = 'Nenhum registro encontrado';
        $this->translate->toolbar->pagination->items->info = 'Mostrando {{start}} - {{end}} de {{total}} registros';
        $this->translate->toolbar->pagination->items->default->first = 'Primeira';
        $this->translate->toolbar->pagination->items->default->prev = 'Anterior';
        $this->translate->toolbar->pagination->items->default->next = 'Próxima';
        $this->translate->toolbar->pagination->items->default->last = 'Última';
        $this->translate->toolbar->pagination->items->default->more = 'Mais';
        $this->translate->toolbar->pagination->items->default->input = 'Página';
        $this->translate->toolbar->pagination->items->default->select = 'Selecione o tamanho da página';
        $this->translate->toolbar->pagination->items->default->all = 'Todos';
    }

    /**
     * @param $data
     */
    private function instanciaObj(&$data)
    {
        $data = new \stdClass();
    }

    /**
     * Rota para as requisições ajax
     * @param string $ajaxUrl
     */
    public function setAjaxUrl(string $ajaxUrl): void
    {
        $this->data->source->read->url = $ajaxUrl;
    }

    /**
     * Metodo das requisições ajax
     * @param string $ajaxUrl
     */
    public function setAjaxUrlMethod(string $ajaxUrl): void
    {
        $this->data->source->read->method = $ajaxUrl;
    }

    /**
     * Converte a paginação padrão do Laravel para a paginação padrão do KTDatatables
     * @param $list
     */
    public static function convertPaginationLaravelToKTDatatables($list)
    {
        $result = new \stdClass();
        $result->meta = new \stdClass();
        $result->meta->page = $list->currentPage();
        $result->meta->pages = $list->lastPage();
        $result->meta->perpage = $list->perPage();
        $result->meta->total = $list->total();

        if (request()->get('sort')) {
            $result->sort = new \stdClass();
            $result->sort->field = request()->get('sort')['field'];
            $result->sort->sort = request()->get('sort')['sort'];
        }
        $result->data = $list->items();

        return $result;
    }

    /**
     * Rota para as requisições ajax
     * @return string
     */
    public function getAjaxUrl(): string
    {
        return $this->data->source->read->url;
    }

    /**
     * Metodo das requisições ajax
     * @return string
     */
    public function getAjaxUrlMethod(): string
    {
        return $this->data->source->read->method;
    }

    /**
     * Adiciona uma coluna na tabela. Para personalizar o html da linha é preciso implementar o método
     * template: function() {}; na view datatable.
     * @param $fieldName
     * @param $fieldTitle
     * @return DatatableColumn
     */
    public function addColumn($fieldName, $fieldTitle)
    {
        $column = new DatatableColumn();
        $column->set('field', $fieldName)
            ->set('title', $fieldTitle);
        $this->columns->add($column);
        return $column;
    }

    /**
     * Adiciona uma coluna do tipo checkbox
     * @return DatatableColumn
     */
    public function addCheckbox()
    {
        $column = new DatatableColumn();
        $column->set('field', 'check')
            ->set('autoHide', false)
            ->set('title', '#')
            ->set('width', 20)
            ->set('selector', true);
        $this->columns->add($column);
        return $column;
    }

    /**
     * Adiciona uma coluna do ID
     * @return DatatableColumn
     */
    public function addIDColumn()
    {
        $column = new DatatableColumn();
        $column->set('field', 'id')
            ->set('title', '#')
            ->set('sortable', 'asc')
            ->set('width', 35)
            ->set('type', 'number')
            ->set('textAlign', 'center');
        $this->columns->add($column);
        return $column;
    }

    /**
     * Adiciona uma coluna do tipo ações. Para adicionar ações é preciso implementar o método
     * template: function() {}; na view datatable.
     * @param $fieldTitle
     * @return DatatableColumn
     */
    public function addActions($fieldTitle)
    {
        $column = new DatatableColumn();
        $column->set('field', 'actions')
            ->set('title', $fieldTitle)
            ->set('sortable', false)
            ->set('width', 40)
            ->set('overflow', 'visible')
            ->set('textAlign', 'center');
        $this->columns->add($column);
        return $column;
    }

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
