<?php
/**
 * Telefone Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class Telefone extends TRecord
{
    const TABLENAME = 'public.telefone';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $pessoa;
    private $tipo_telefone;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('numero');
        parent::addAttribute('obs');
        parent::addAttribute('tipo_telefone_id');
        parent::addAttribute('pessoa_id');
    }

    
    /**
     * Method set_pessoa
     * Sample of usage: $telefone->pessoa = $object;
     * @param $object Instance of Pessoa
     */
    public function set_pessoa(Pessoa $object)
    {
        $this->pessoa = $object;
        $this->pessoa_id = $object->id;
    }
    
    /**
     * Method get_pessoa
     * Sample of usage: $telefone->pessoa->attribute;
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
     * Method set_tipo_telefone
     * Sample of usage: $telefone->tipo_telefone = $object;
     * @param $object Instance of TipoTelefone
     */
    public function set_tipo_telefone(TipoTelefone $object)
    {
        $this->tipo_telefone = $object;
        $this->tipo_telefone_id = $object->id;
    }
    
    /**
     * Method get_tipo_telefone
     * Sample of usage: $telefone->tipo_telefone->attribute;
     * @returns TipoTelefone instance
     */
    public function get_tipo_telefone()
    {
        // loads the associated object
        if (empty($this->tipo_telefone))
            $this->tipo_telefone = new TipoTelefone($this->tipo_telefone_id);
    
        // returns the associated object
        return $this->tipo_telefone;
    }
    


}
