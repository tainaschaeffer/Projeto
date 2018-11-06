<?php
/**
 * PessoaForm Form
 * @author  Tainá Schaeffer 18-06-2018
 */
class PessoaForm extends TPage
{
    protected $form; // form
    
    /**
     * Form constructor
     * @param $param Request
     */
    public function __construct( $param )
    {
        parent::__construct();
        
        // creates the form
        $this->form = new TQuickForm('form_Pessoa');
        $this->form->class = 'tform'; // change CSS class
        $this->form = new BootstrapFormWrapper($this->form);
        $this->form->style = 'display: table;width:100%'; // change style
        
        // define the form title
        $this->form->setFormTitle(_t('Person'));

        // create the form fields
        $id = new TEntry('id');
        $nome = new TEntry('nome');
        $apelido = new TEntry('apelido');
        $data_nascimento = new TDate('data_nascimento');
        $razao_social = new TEntry('razao_social');
        $cpf = new TEntry('cpf');
        $cnpj = new TEntry('cnpj');
        $rg = new TEntry('rg');
        $rg_emissor = new TEntry('rg_emissor');
        $rg_estado = new TEntry('rg_estado');
        $nacionalidade_id = new TDBCombo('nacionalidade_id', 'permission', 'Nacionalidade', 'id', 'nome', 'nome');
        $estado_civil_id = new TDBCombo('estado_civil_id', 'permission', 'EstadoCivil', 'id', 'nome', 'nome');
        $sexo_id = new TDBCombo('sexo_id', 'permission', 'Sexo', 'id', 'nome', 'nome');
        $tipo_pessoa_id = new TDBCombo('tipo_pessoa_id', 'permission', 'TipoPessoa', 'id', 'nome', 'nome');

        $tipo_pessoa_id->setChangeAction( new TAction( array($this, 'onChangeTipoPessoa')) );

        $id->setEditable(FALSE);

        $nacionalidade_id->setSize('20%');
        $rg->setSize('15%');
        $rg_estado->setSize('10%');
        $rg_emissor->setSize('10%');
        
        $data_nascimento->setMask('dd/mm/yyyy');
        $cnpj->setMask('99.999.999/9999-99', true);
        $cpf->setMask('999.999.999-99', true);

        $tipo_pessoa_id->addValidation(_t('Type person'), new TRequiredValidator);
        $nome->addValidation(_t('Name'), new TRequiredValidator);

        // creates the action button
        $button_nacionalidade = new TButton('button_nacionalidade');
        $button_nacionalidade->setAction(new TAction(array($this, 'onAdicionarNacionalidade')), '');
        $button_nacionalidade->setImage('fa:plus green');

        $button_endereco = new TButton('button_endereco');
        $button_endereco->setAction(new TAction(array($this, 'onAdicionarEndereco')), 'Endereço');
        $button_endereco->setImage('fa:address-book green');

        $button_telefone = new TButton('button_telefone');
        $button_telefone->setAction(new TAction(array($this, 'onAdicionarTelefone')), 'Telefone');
        $button_telefone->setImage('fa:phone green');

        //$button_email = new TButton('button_email');
        //$button_email->setAction(new TAction(array('EmailFormList', 'onEdit')), 'Email');
        //$button_email->setImage('fa:plus green');

        // add the fields
        $this->form->addQuickField(_t('ID').':', $id,  '10%' );
        $this->form->addQuickField(_t('Type person').': (*)', $tipo_pessoa_id,  '20%' );
        $this->form->addQuickField(_t('Name').': (*)', $nome,  '50%' );
        $this->form->addQuickField(_('Apelido').':', $apelido,  '50%' );
        $this->form->addQuickField(_('Data nascimento/início').':', $data_nascimento,  '20%' );
        $this->form->addQuickField(_('Razão social').':', $razao_social,  '50%' );
        $this->form->addQuickField(_('CPF'), $cpf,  '20%' );
        $this->form->addQuickField(_('CNPJ').':', $cnpj,  '20%' );
        $this->form->addQuickFields(_('RG').':', array($rg, new TLabel('<b>'.'Órgão emissor'.':</b>'), $rg_emissor, new TLabel('<b>'.'Estado'.':</b>'), $rg_estado) );
        $this->form->addQuickFields(_('Nacionalidade').':', array($nacionalidade_id, $button_nacionalidade) );
        $this->form->addQuickField(_('Estado civil').':', $estado_civil_id,  '20%' );
        $this->form->addQuickField(_('Sexo').':', $sexo_id,  '20%' );

        $this->form->addQuickFields('', array($button_endereco, new TLabel(' '), $button_telefone) );

        // create the form actions
        $btn = $this->form->addQuickAction(_t('Save'), new TAction(array($this, 'onSave')), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addQuickAction(_t('New'),  new TAction(array($this, 'onClear')), 'bs:plus-sign green');
        
        // vertical box container
        $container = new TVBox;
        $container->style = 'width: 100%';
        $container->add(new TXMLBreadCrumb('menu.xml', 'PessoaList'));
        $container->add(TPanelGroup::pack(_t('Person'), $this->form));
        
        parent::add($container);
    }

    public function onAdicionarEndereco($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data); // put the data back to the form
        
        $objects = TSession::getValue('session_endereco');
        $objects[ $data->id ] = $data;
        
        TSession::setValue('session_endereco', $objects);

        AdiantiCoreApplication::loadPage('EnderecoFormList', 'onLoad', $param);
    }

    public function onAdicionarTelefone($param)
    {
        $data = $this->form->getData();
        $this->form->setData($data); // put the data back to the form
        
        $objects = TSession::getValue('session_telefone');
        $objects[ $data->id ] = $data;
        
        TSession::setValue('session_telefone', $objects);

        AdiantiCoreApplication::loadPage('TelefoneFormList', 'onEdit', $param);
    }

    public static function onChangeTipoPessoa($param)
    {
        if ((int)$param['tipo_pessoa_id'] == 1)
        {
            TQuickForm::showField('form_Pessoa', 'apelido');
            TQuickForm::showField('form_Pessoa', 'cpf');
            TQuickForm::showField('form_Pessoa', 'rg');
            TQuickForm::showField('form_Pessoa', 'rg_estado');
            TQuickForm::showField('form_Pessoa', 'rg_emissor');
            TQuickForm::showField('form_Pessoa', 'estado_civil_id');
            TQuickForm::showField('form_Pessoa', 'sexo_id');
            TQuickForm::showField('form_Pessoa', 'nacionalidade_id');

            TQuickForm::hideField('form_Pessoa', 'razao_social');
            TQuickForm::hideField('form_Pessoa', 'cnpj');        
        }
        else
        {
            TQuickForm::hideField('form_Pessoa', 'apelido');
            TQuickForm::hideField('form_Pessoa', 'cpf');
            TQuickForm::hideField('form_Pessoa', 'rg');
            TQuickForm::hideField('form_Pessoa', 'rg_estado');
            TQuickForm::hideField('form_Pessoa', 'rg_emissor');
            TQuickForm::hideField('form_Pessoa', 'estado_civil_id');
            TQuickForm::hideField('form_Pessoa', 'sexo_id');
            TQuickForm::hideField('form_Pessoa', 'nacionalidade_id');

            TQuickForm::showField('form_Pessoa', 'razao_social');
            TQuickForm::showField('form_Pessoa', 'cnpj');  
        }
    }

    public function onAdicionarNacionalidade($param)
    {
        $data = $this->form->getData();
        
        $objects = TSession::getValue('session_nacionalidade');
        $objects[ $data->id ] = $param;
        
        TSession::setValue('session_nacionalidade', $objects);

        AdiantiCoreApplication::loadPage('NacionalidadeFormList', 'onEdit', $objects);
    }



    public function onAtualizarNacionalidade()
    {
        TCombo::reload('form_Pessoa', 'nacionalidade_id', $options);
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
            
            $object = new Pessoa;  // create an empty object
            $data = $this->form->getData(); // get form data as array

            !empty($data->cpf) ? $data->cpf = TUtil::limpaCPF_CNPJ($data->cpf) : $data->cnpj = TUtil::limpaCPF_CNPJ($data->cnpj);

            $object->fromArray( (array) $data); // load the object with data
            $object->store(); // save the object
            
            // get the generated id
            $data->id = $object->id;
            
            $this->form->setData($data); // fill form data
            TTransaction::close(); // close the transaction
            
            new TMessage('info', TAdiantiCoreTranslator::translate('Record saved'));
            $this->onEdit($param);

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
        $this->form->clear(TRUE);
    }
    
    /**
     * Load object to form data
     * @param $param Request
     */
    public function onEdit( $param )
    {
        try
        {
            if (isset($param['key']) || isset($param['id']))
            {
                !empty($param['key']) ? $key = $param['key'] : $key = $param['id'];  // get the parameter $key
                TTransaction::open('permission'); // open a transaction
                $object = new Pessoa($key); // instantiates the Active Record

                $object->data_nascimento = TDate::date2br($object->data_nascimento);
                
                !empty($object->cpf) ? TUtil::adicionaMascaraCPF($object->cpf) : "";
                !empty($object->cnpj) ? TUtil::adicionaMascaraCNPJ($object->cnpj) : "";

                $obj['tipo_pessoa_id'] = $object->tipo_pessoa_id;
                self::onChangeTipoPessoa($obj);

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
}
