<?php
/**
 * TelefoneFormList Registration
 * @author  Tainá Schaeffer 18-06-2018
 */
class TelefoneFormList extends TWindow
{
    protected $form; // form
    protected $datagrid; // datagrid
    protected $pageNavigation;
    
    use Adianti\Base\AdiantiStandardFormListTrait; // standard form/list methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission');            // defines the database
        $this->setActiveRecord('Telefone');   // defines the active record
        $this->setDefaultOrder('id', 'asc');         // defines the default order
        // $this->setCriteria($criteria) // define a standard filter
        
        // creates the form
        $this->form = new TQuickForm('form_Telefone');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        $this->form->setFormTitle('Telefone');
        


        // create the form fields
        $id = new TEntry('id');
        $numero = new TEntry('numero');
        $obs = new TText('obs');
        $tipo_telefone_id = new TEntry('tipo_telefone_id');
        $pessoa_id = new TEntry('pessoa_id');


        // add the fields
        $this->form->addQuickField('Id', $id,  '50%' );
        $this->form->addQuickField('Numero', $numero,  '100%' );
        $this->form->addQuickField('Obs', $obs,  '100%' );
        $this->form->addQuickField('Tipo Telefone Id', $tipo_telefone_id,  '50%' );
        $this->form->addQuickField('Pessoa Id', $pessoa_id,  '50%' );



        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        
        // creates a DataGrid
        $this->datagrid = new TDataGrid;
        $this->datagrid = new BootstrapDatagridWrapper($this->datagrid);
        $this->datagrid->style = 'width: 100%';
        // $this->datagrid->datatable = 'true';
        // $this->datagrid->enablePopover('Popover', 'Hi <b> {name} </b>');
        

        // creates the datagrid columns
        $column_id = new TDataGridColumn('id', 'Id', 'left');
        $column_numero = new TDataGridColumn('numero', 'Numero', 'left');
        $column_obs = new TDataGridColumn('obs', 'Obs', 'left');
        $column_tipo_telefone_id = new TDataGridColumn('tipo_telefone_id', 'Tipo Telefone Id', 'left');
        $column_pessoa_id = new TDataGridColumn('pessoa_id', 'Pessoa Id', 'left');


        // add the columns to the DataGrid
        $this->datagrid->addColumn($column_id);
        $this->datagrid->addColumn($column_numero);
        $this->datagrid->addColumn($column_obs);
        $this->datagrid->addColumn($column_tipo_telefone_id);
        $this->datagrid->addColumn($column_pessoa_id);

        
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
        
        // create the page navigation
        $this->pageNavigation = new TPageNavigation;
        $this->pageNavigation->setAction(new TAction(array($this, 'onReload')));
        $this->pageNavigation->setWidth($this->datagrid->getWidth());
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Title', $this->form));
        $container->add(TPanelGroup::pack('', $this->datagrid, $this->pageNavigation));
        
        parent::add($container);
    }
}
