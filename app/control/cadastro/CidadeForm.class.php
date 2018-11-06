<?php
/**
 * CidadeForm Registration
 * @author  Tainá Schaeffer 18-06-2018
 */
class CidadeForm extends TPage
{
    protected $form; // form
    
    use Adianti\Base\AdiantiStandardFormTrait; // Standard form methods
    
    /**
     * Class constructor
     * Creates the page and the registration form
     */
    function __construct()
    {
        parent::__construct();
        
        $this->setDatabase('permission');              // defines the database
        $this->setActiveRecord('Cidade');     // defines the active record
        
        // creates the form
        $this->form = new TQuickForm('form_Cidade');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle('Cidade');
        


        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $estado_id = new TEntry('estado_id');


        // add the fields
        $this->form->addQuickField('Id', $id,  '50%' );
        $this->form->addQuickField('Nome', $nome,  '100%' );
        $this->form->addQuickField('Estado Id', $estado_id,  '50%' );



        
        if (!empty($id))
        {
            $id->setEditable(FALSE);
        }
        
        /** samples
         $this->form->addQuickFields('Date', array($date1, new TLabel('to'), $date2)); // side by side fields
         $fieldX->addValidation( 'Field X', new TRequiredValidator ); // add validation
         $fieldX->setSize( 100, 40 ); // set size
         **/
         
        // create the form actions
        $btn = $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onEdit')), 'bs:plus-sign green');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 90%';
        // $container->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $container->add(TPanelGroup::pack('Title', $this->form));
        
        parent::add($container);
    }
}
