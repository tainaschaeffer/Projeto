<?php
/**
 * PessoaList Listing
 * @author  Tainá Schaeffer 18-06-2018
 */
class PessoaList extends TPage
{
    private $form; // form
    private $datagrid; // listing
    private $pageNavigation;
    private $formgrid;
    private $loaded;
    private $deleteButton;
    
    /**
     * Class constructor
     * Creates the page, the form and the listing
     */
    public function __construct()
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_search_Pessoa');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Pessoa');
        
        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $tipo_pessoa_id = new TDBCombo('tipo_pessoa_id', 'permission', 'TipoPessoa', 'id', 'nome', 'nome');

        // add the fields
        $this->form->addQuickField(_t('ID').':', $id,  '10%' );
        $this->form->addQuickField(_('Tipo pessoa').':', $tipo_pessoa_id,  '20%' );
        $this->form->addQuickField(_t('Name').':', $nome,  '50%' );
        
        // keep the form filled during navigation with session data
        $this->form->setData( TSession::getValue('Pessoa_filter_data') );
        
        // add the search form actions
        $this->form->addQuickAction(_t('Find'), new TAction(array($this, 'onSearch')), 'fa:search');
        $this->form->addQuickAction(_t('New'),  new TAction(array('PessoaForm', 'onEdit')), 'bs:plus-sign green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        
        // creates the datagrid columns
        $column_id               = new TDataGridColumn('id', _t('ID'), 'right');
        $column_tipo_pessoa_id   = new TDataGridColumn('tipo_pessoa_nome', _('Tipo pessoa'), 'right');
        $column_nome             = new TDataGridColumn('nome', _t('Name'), 'left');
        $column_data_nascimento  = new TDataGridColumn('data_nascimento', _('Data Nascimento'), 'left');
        $column_identificacao    = new TDataGridColumn('identificacao', _('Identificação'), 'right');
        $column_nacionalidade_id = new TDataGridColumn('nacionalidade_nome', _('Nacionalidade'), 'right');
        $column_estado_civil_id  = new TDataGridColumn('estado_civil_nome', _('Estado civil'), 'right');
        $column_sexo_id          = new TDataGridColumn('sexo_nome', _('Sexo'), 'right');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_tipo_pessoa_id);
        $this->datagrid->addColumn($column_nome);
        $this->datagrid->addColumn($column_data_nascimento);
        $this->datagrid->addColumn($column_identificacao);
        $this->datagrid->addColumn($column_nacionalidade_id);
        $this->datagrid->addColumn($column_estado_civil_id);
        $this->datagrid->addColumn($column_sexo_id);

        // create EDIT action
        $action_edit = new TDataGridAction(array('PessoaForm', 'onEdit'));
        //$action_edit->setUseButton(TRUE);
        //$action_edit->setButtonClass('btn btn-default');
        $action_edit->setLabel(_t('Edit'));
        $action_edit->setImage('fa:pencil-square-o blue fa-lg');
        $action_edit->setField('id');
        $this->datagrid->addAction($action_edit);
        
        // create DELETE action
        $action_del = new TDataGridAction(array($this, 'onDelete'));
        //$action_del->setUseButton(TRUE);
        //$action_del->setButtonClass('btn btn-default');
        $action_del->setLabel(_t('Delete'));
        $action_del->setImage('fa:trash-o red fa-lg');
        $action_del->setField('id');
        $this->datagrid->addAction($action_del);
        
        // create the datagrid model
        $this->datagrid->createModel();
        
        // creates the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack(_('Pessoa'), $this->form));
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
    
    /**
     * Inline record editing
     * @param $param Array containing:
     *              key: object ID value
     *              field name: object attribute to be updated
     *              value: new attribute content 
     */
    public function onInlineEdit($param)
    {
        try
        {
            // get the parameter $key
            $field = $param['field'];
            $key   = $param['key'];
            $value = $param['value'];
            
            TTransaction::open('permission'); // open a transaction with database
            $object = new Pessoa($key); // instantiates the Active Record
            $object->{$field} = $value;
            $object->store(); // update the object in the database
            TTransaction::close(); // close the transaction
            
            $this->onReload($param); // reload the listing
            new TMessage('info', "Record Updated");
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Register the filter in the session
     */
    public function onSearch()
    {
        // get the search form data
        $data = $this->form->getData();
        
        // clear session filters
        TSession::setValue('PessoaList_filter_id',   NULL);
        TSession::setValue('PessoaList_filter_nome',   NULL);
        TSession::setValue('PessoaList_filter_apelido',   NULL);
        TSession::setValue('PessoaList_filter_data_nascimento',   NULL);
        TSession::setValue('PessoaList_filter_razao_social',   NULL);
        TSession::setValue('PessoaList_filter_cpf',   NULL);
        TSession::setValue('PessoaList_filter_cnpj',   NULL);
        TSession::setValue('PessoaList_filter_rg',   NULL);
        TSession::setValue('PessoaList_filter_rg_emissor',   NULL);
        TSession::setValue('PessoaList_filter_rg_estado',   NULL);
        TSession::setValue('PessoaList_filter_nacionalidade_id',   NULL);
        TSession::setValue('PessoaList_filter_estado_civil_id',   NULL);
        TSession::setValue('PessoaList_filter_sexo_id',   NULL);
        TSession::setValue('PessoaList_filter_tipo_pessoa_id',   NULL);

        if (isset($data->id) AND ($data->id)) {
            $filter = new TFilter('id', 'like', "%{$data->id}%"); // create the filter
            TSession::setValue('PessoaList_filter_id',   $filter); // stores the filter in the session
        }


        if (isset($data->nome) AND ($data->nome)) {
            $filter = new TFilter('nome', 'like', "%{$data->nome}%"); // create the filter
            TSession::setValue('PessoaList_filter_nome',   $filter); // stores the filter in the session
        }


        if (isset($data->apelido) AND ($data->apelido)) {
            $filter = new TFilter('apelido', 'like', "%{$data->apelido}%"); // create the filter
            TSession::setValue('PessoaList_filter_apelido',   $filter); // stores the filter in the session
        }


        if (isset($data->data_nascimento) AND ($data->data_nascimento)) {
            $filter = new TFilter('data_nascimento', 'like', "%{$data->data_nascimento}%"); // create the filter
            TSession::setValue('PessoaList_filter_data_nascimento',   $filter); // stores the filter in the session
        }


        if (isset($data->razao_social) AND ($data->razao_social)) {
            $filter = new TFilter('razao_social', 'like', "%{$data->razao_social}%"); // create the filter
            TSession::setValue('PessoaList_filter_razao_social',   $filter); // stores the filter in the session
        }


        if (isset($data->cpf) AND ($data->cpf)) {
            $filter = new TFilter('cpf', 'like', "%{$data->cpf}%"); // create the filter
            TSession::setValue('PessoaList_filter_cpf',   $filter); // stores the filter in the session
        }


        if (isset($data->cnpj) AND ($data->cnpj)) {
            $filter = new TFilter('cnpj', 'like', "%{$data->cnpj}%"); // create the filter
            TSession::setValue('PessoaList_filter_cnpj',   $filter); // stores the filter in the session
        }


        if (isset($data->rg) AND ($data->rg)) {
            $filter = new TFilter('rg', 'like', "%{$data->rg}%"); // create the filter
            TSession::setValue('PessoaList_filter_rg',   $filter); // stores the filter in the session
        }


        if (isset($data->rg_emissor) AND ($data->rg_emissor)) {
            $filter = new TFilter('rg_emissor', 'like', "%{$data->rg_emissor}%"); // create the filter
            TSession::setValue('PessoaList_filter_rg_emissor',   $filter); // stores the filter in the session
        }


        if (isset($data->rg_estado) AND ($data->rg_estado)) {
            $filter = new TFilter('rg_estado', 'like', "%{$data->rg_estado}%"); // create the filter
            TSession::setValue('PessoaList_filter_rg_estado',   $filter); // stores the filter in the session
        }


        if (isset($data->nacionalidade_id) AND ($data->nacionalidade_id)) {
            $filter = new TFilter('nacionalidade_id', 'like', "%{$data->nacionalidade_id}%"); // create the filter
            TSession::setValue('PessoaList_filter_nacionalidade_id',   $filter); // stores the filter in the session
        }


        if (isset($data->estado_civil_id) AND ($data->estado_civil_id)) {
            $filter = new TFilter('estado_civil_id', 'like', "%{$data->estado_civil_id}%"); // create the filter
            TSession::setValue('PessoaList_filter_estado_civil_id',   $filter); // stores the filter in the session
        }


        if (isset($data->sexo_id) AND ($data->sexo_id)) {
            $filter = new TFilter('sexo_id', 'like', "%{$data->sexo_id}%"); // create the filter
            TSession::setValue('PessoaList_filter_sexo_id',   $filter); // stores the filter in the session
        }


        if (isset($data->tipo_pessoa_id) AND ($data->tipo_pessoa_id)) {
            $filter = new TFilter('tipo_pessoa_id', 'like', "%{$data->tipo_pessoa_id}%"); // create the filter
            TSession::setValue('PessoaList_filter_tipo_pessoa_id',   $filter); // stores the filter in the session
        }

        
        // fill the form with data again
        $this->form->setData($data);
        
        // keep the search data in the session
        TSession::setValue('Pessoa_filter_data', $data);
        
        $param=array();
        $param['offset']    =0;
        $param['first_page']=1;
        $this->onReload($param);
    }
    
    /**
     * Load the datagrid with data
     */
    public function onReload($param = NULL)
    {
        try
        {
            // open a transaction with database 'permission'
            TTransaction::open('permission');
            
            // creates a repository for Pessoa
            $repository = new TRepository('Pessoa');
            $limit = 10;
            // creates a criteria
            $criteria = new TCriteria;
            
            // default order
            if (empty($param['order']))
            {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            $criteria->setProperties($param); // order, offset
            $criteria->setProperty('limit', $limit);
            

            if (TSession::getValue('PessoaList_filter_id')) {
                $criteria->add(TSession::getValue('PessoaList_filter_id')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_nome')) {
                $criteria->add(TSession::getValue('PessoaList_filter_nome')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_apelido')) {
                $criteria->add(TSession::getValue('PessoaList_filter_apelido')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_data_nascimento')) {
                $criteria->add(TSession::getValue('PessoaList_filter_data_nascimento')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_razao_social')) {
                $criteria->add(TSession::getValue('PessoaList_filter_razao_social')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_cpf')) {
                $criteria->add(TSession::getValue('PessoaList_filter_cpf')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_cnpj')) {
                $criteria->add(TSession::getValue('PessoaList_filter_cnpj')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_rg')) {
                $criteria->add(TSession::getValue('PessoaList_filter_rg')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_rg_emissor')) {
                $criteria->add(TSession::getValue('PessoaList_filter_rg_emissor')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_rg_estado')) {
                $criteria->add(TSession::getValue('PessoaList_filter_rg_estado')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_nacionalidade_id')) {
                $criteria->add(TSession::getValue('PessoaList_filter_nacionalidade_id')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_estado_civil_id')) {
                $criteria->add(TSession::getValue('PessoaList_filter_estado_civil_id')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_sexo_id')) {
                $criteria->add(TSession::getValue('PessoaList_filter_sexo_id')); // add the session filter
            }


            if (TSession::getValue('PessoaList_filter_tipo_pessoa_id')) {
                $criteria->add(TSession::getValue('PessoaList_filter_tipo_pessoa_id')); // add the session filter
            }

            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            if (is_callable($this->transformCallback))
            {
                call_user_func($this->transformCallback, $objects, $param);
            }
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
                    $object->data_nascimento = TDate::date2br($object->data_nascimento);
                    $this->datagrid->addItem($object);
                }
            }
            
            // reset the criteria for record count
            $criteria->resetProperties();
            $count= $repository->count($criteria);
            
            $this->pageNavigation->setCount($count); // count of records
            $this->pageNavigation->setProperties($param); // order, page
            $this->pageNavigation->setLimit($limit); // limit
            
            // close the transaction
            TTransaction::close();
            $this->loaded = true;
        }
        catch (Exception $e) // in case of exception
        {
            // shows the exception error message
            new TMessage('error', $e->getMessage());
            // undo all pending operations
            TTransaction::rollback();
        }
    }
    
    /**
     * Ask before deletion
     */
    public function onDelete($param)
    {
        // define the delete action
        $action = new TAction(array($this, 'Delete'));
        $action->setParameters($param); // pass the key parameter ahead
        
        // shows a dialog to the user
        new TQuestion(AdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
    }
    
    /**
     * Delete a record
     */
    public function Delete($param)
    {
        try
        {
            $key=$param['key']; // get the parameter $key
            TTransaction::open('permission'); // open a transaction with database
            $object = new Pessoa($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', AdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    



    
    /**
     * method show()
     * Shows the page
     */
    public function show()
    {
        // check if the datagrid is already loaded
        if (!$this->loaded AND (!isset($_GET['method']) OR !(in_array($_GET['method'],  array('onReload', 'onSearch')))) )
        {
            if (func_num_args() > 0)
            {
                $this->onReload( func_get_arg(0) );
            }
            else
            {
                $this->onReload();
            }
        }
        parent::show();
    }
}
