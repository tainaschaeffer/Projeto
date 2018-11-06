<?php
/**
 * Endereco Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class Endereco extends TRecord
{
    const TABLENAME = 'public.endereco';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'max'; // {max, serial}
    
    
    private $cidade;
    private $pessoa;
    private $tipo_endereco;
    private $tipo_logradouro;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('logradouro');
        parent::addAttribute('numero');
        parent::addAttribute('bairro');
        parent::addAttribute('complemento');
        parent::addAttribute('obs');
        parent::addAttribute('tipo_endereco_id');
        parent::addAttribute('tipo_logradouro_id');
        parent::addAttribute('pessoa_id');
        parent::addAttribute('cidade_id');
    }

    
    /**
     * Method set_cidade
     * Sample of usage: $endereco->cidade = $object;
     * @param $object Instance of Cidade
     */
    public function set_cidade(Cidade $object)
    {
        $this->cidade = $object;
        $this->cidade_id = $object->id;
    }
    
    /**
     * Method get_cidade
     * Sample of usage: $endereco->cidade->attribute;
     * @returns Cidade instance
     */
    public function get_cidade()
    {
        // loads the associated object
        if (empty($this->cidade))
            $this->cidade = new Cidade($this->cidade_id);
    
        // returns the associated object
        return $this->cidade;
    }
    
    
    /**
     * Method set_pessoa
     * Sample of usage: $endereco->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $endereco->pessoa->attribute;
     * @returns Pessoa instance
     */
    public function get_pessoa()
    {
        // loads the associated object
        if (empty($this->pessoa))
            $this->pessoa = new Pessoa($this->pessoa_id);
    
        // returns the associated object
        return $this->pessoa;
    }
    
    
    /**
     * Method set_tipo_endereco
     * Sample of usage: $endereco->tipo_endereco = $object;
     * @param $object Instance of TipoEndereco
     */
    public function set_tipo_endereco(TipoEndereco $object)
    {
        $this->tipo_endereco = $object;
        $this->tipo_endereco_id = $object->id;
    }
    
    /**
     * Method get_tipo_endereco
     * Sample of usage: $endereco->tipo_endereco->attribute;
     * @returns TipoEndereco instance
     */
    public function get_tipo_endereco()
    {
        // loads the associated object
        if (empty($this->tipo_endereco))
            $this->tipo_endereco = new TipoEndereco($this->tipo_endereco_id);
    
        // returns the associated object
        return $this->tipo_endereco;
    }
    
    
    /**
     * Method set_tipo_logradouro
     * Sample of usage: $endereco->tipo_logradouro = $object;
     * @param $object Instance of TipoLogradouro
     */
    public function set_tipo_logradouro(TipoLogradouro $object)
    {
        $this->tipo_logradouro = $object;
        $this->tipo_logradouro_id = $object->id;
    }
    
    /**
     * Method get_tipo_logradouro
     * Sample of usage: $endereco->tipo_logradouro->attribute;
     * @returns TipoLogradouro instance
     */
    public function get_tipo_logradouro()
    {
        // loads the associated object
        if (empty($this->tipo_logradouro))
            $this->tipo_logradouro = new TipoLogradouro($this->tipo_logradouro_id);
    
        // returns the associated object
        return $this->tipo_logradouro;
    }
    


}
