<?php
/**
 * Cidade Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class Cidade extends TRecord
{
    const TABLENAME = 'public.cidade';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $estado;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('estado_id');
    }

    
    /**
     * Method set_estado
     * Sample of usage: $cidade->estado = $object;
     * @param $object Instance of Estado
     */
    public function set_estado(Estado $object)
    {
        $this->estado = $object;
        $this->estado_id = $object->id;
    }
    
    /**
     * Method get_estado
     * Sample of usage: $cidade->estado->attribute;
     * @returns Estado instance
     */
    public function get_estado()
    {
        // loads the associated object
        if (empty($this->estado))
            $this->estado = new Estado($this->estado_id);
    
        // returns the associated object
        return $this->estado;
    }
    


}
