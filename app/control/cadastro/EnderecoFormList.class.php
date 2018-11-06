<?php
/**
 * EnderecoFormList Form List
 * @author  Tainá Schaeffer 18-06-2018
 */
class EnderecoFormList extends TWindow
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    protected $loaded;
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Endereco');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Endereco');
        
        // create the form fields
        $id                 = new TEntry('id');
        $logradouro         = new TEntry('logradouro');
        $numero             = new TEntry('numero');
        $bairro             = new TEntry('bairro');
        $complemento        = new TEntry('complemento');
        $obs                = new TText('obs');
        $tipo_endereco_id   = new TDBCombo('tipo_endereco_id', 'permission', 'TipoEndereco', 'id', 'nome', 'nome');
        $tipo_logradouro_id = new TDBCombo('tipo_logradouro_id', 'permission', 'TipoLogradouro', 'id', 'nome', 'nome');
        $pessoa_id          = new TEntry('pessoa_id');
        $pessoa_nome        = new TEntry('pessoa_nome');
        $cidade_id          = new TDBUniqueSearch('cidade_id', 'permission', 'Cidade', 'id', 'nome', 'nome');

        $id->setEditable(FALSE);
        $pessoa_id->setEditable(FALSE);
        $pessoa_nome->setEditable(FALSE);

        $obs->setSize('20%', '50%');
        $numero->setSize('10%');
        $pessoa_id->setSize('10%');
        $pessoa_nome->setSize('50%');
        $bairro->setSize('20%');

        // add the fields
        $this->form->addQuickField(_('Cód').':', $id,  '10%' );
        $this->form->addQuickFields(_('Pessoa').':', array($pessoa_id, $pessoa_nome));
        $this->form->addQuickField(_('Tipo endereço').':', $tipo_endereco_id,  '20%' );
        $this->form->addQuickField(_('Tipo logradouro').':', $tipo_logradouro_id,  '20%' );
        $this->form->addQuickField(_('Logradouro').':', $logradouro,  '50%' );
        $this->form->addQuickFields(_('Número').':', array($numero, new TLabel('<b>'.'Bairro'.':</b>'), $bairro));
        $this->form->addQuickField(_('Complemento').':', $complemento,  '20%' );
        $this->form->addQuickField(_('Observação').':', $obs );
        $this->form->addQuickField(_('Cidade').':', $cidade_id,  '50%' );

        // create the form actions
        $btn = $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        $this->form->addQuickAction(_('Fechar'),  new TAction(array($this, 'onClose')), 'fa:times green');
        
        // creates a Datagrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', _('Cód'), 'left');
        $column_logradouro = new TDataGridColumn('logradouro', _('Logradouro'), 'left');
        $column_numero = new TDataGridColumn('numero', _('Número'), 'left');
        $column_bairro = new TDataGridColumn('bairro', _('Bairro'), 'left');
        $column_complemento = new TDataGridColumn('complemento', _('Complemento'), 'left');
        $column_obs = new TDataGridColumn('obs', _('Observação'), 'left');
        $column_tipo_endereco_id = new TDataGridColumn('tipo_endereco_id', _('Tipo endereço'), 'left');
        $column_tipo_logradouro_id = new TDataGridColumn('tipo_logradouro_id', _('Tipo logradouro'), 'left');
        $column_pessoa_id = new TDataGridColumn('pessoa_id', _('Pessoa'), 'left');
        $column_cidade_id = new TDataGridColumn('cidade_id', _('Cidade'), 'left');

        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_logradouro);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_bairro);
        $this->datagrid->addColumn($column_complemento);
        $this->datagrid->addColumn($column_obs);
        $this->datagrid->addColumn($column_tipo_endereco_id);
        $this->datagrid->addColumn($column_tipo_logradouro_id);
        $this->datagrid->addColumn($column_pessoa_id);
        $this->datagrid->addColumn($column_cidade_id);

        // creates two datagrid actions
        $action1 = new TDataGridAction(array($this, 'onEdit'));
        //$action1->setUseButton(TRUE);
        //$action1->setButtonClass('btn btn-default');
        $action1->setLabel(_t('Edit'));
        $action1->setImage('fa:pencil-square-o blue fa-lg');
        $action1->setField('id');
        
        $action2 = new TDataGridAction(array($this, 'onDelete'));
        //$action2->setUseButton(TRUE);
        //$action2->setButtonClass('btn btn-default');
        $action2->setLabel(_t('Delete'));
        $action2->setImage('fa:trash-o red fa-lg');
        $action2->setField('id');
        
        // add the actions to the datagrid
        $this->datagrid->addAction($action1);
        $this->datagrid->addAction($action2);
        
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
        $container->add(TPanelGroup::pack('Endereço', $this->form));
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }

    public function onLoad($param)
    {
        $data              = $this->form->getData();
        $data->pessoa_id   = $param['id'];
        $data->pessoa_nome = $param['nome'];

        $this->form->setData($data);
    }

    public function onClose($param)
    {
        TWindow::closeWindow();
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
            
            // creates a repository for Endereco
            $repository = new TRepository('Endereco');
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
            
            if (TSession::getValue('Endereco_filter'))
            {
                // add the filter stored in the session to the criteria
                $criteria->add(TSession::getValue('Endereco_filter'));
            }
            
            // load the objects according to criteria
            $objects = $repository->load($criteria, FALSE);
            
            $this->datagrid->clear();
            if ($objects)
            {
                // iterate the collection of active records
                foreach ($objects as $object)
                {
                    // add the object inside the datagrid
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
            new TMessage('error', '<b>Error</b> ' . $e->getMessage());
            
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
        new TQuestion(TAdiantiCoreTranslator::translate('Do you really want to delete ?'), $action);
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
            $object = new Endereco($key, FALSE); // instantiates the Active Record
            $object->delete(); // deletes the object from the database
            TTransaction::close(); // close the transaction
            $this->onReload( $param ); // reload the listing
            new TMessage('info', TAdiantiCoreTranslator::translate('Record deleted')); // success message
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', '<b>Error</b> ' . $e->getMessage()); // shows the exception error message
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Save form data
     * @param $param Request
     */
    public function onSave( $param )
    {
        try
        {
            TTransaction::open('permission'); // open a transaction
            
            $this->form->validate(); // validate form data
            
            $object = new Endereco;  // create an empty object
            $data = $this->form->getData(); // get form data as array
            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved')); // success message
            $this->onReload(); // reload the listing
        }
        catch (Exception $e) // in case of exception
        {
            new TMessage('error', $e->getMessage()); // shows the exception error message
            $this->form->setData( $this->form->getData() ); // keep form data
            TTransaction::rollback(); // undo all pending operations
        }
    }
    
    /**
     * Clear form data
     * @param $param Request
     */
    public function onClear( $param )
    {
        $data = $this->form->getData();
        
        $pessoa = new stdClass();
        $pessoa->pessoa_id = $data->pessoa_id;
        $pessoa->pessoa_nome = $data->pessoa_nome;

        $this->form->clear(TRUE);

        $this->form->setData($pessoa);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']))
            {
                $key = $param['key'];  // get the parameter $key
                TTransaction::open('permission'); // open a transaction
                $object = new Endereco($key); // instantiates the Active Record
                $this->form->setData($object); // fill the form
                TTransaction::close(); // close the transaction
            }
            else
            {
                $this->form->clear(TRUE);
            }
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
        if (!$this->loaded AND (!isset($_GET['method']) OR $_GET['method'] !== 'onReload') )
        {
            $this->onReload( func_get_arg(0) );
        }
        parent::show();
    }
}
