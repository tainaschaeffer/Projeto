<?php
/**
 * Estado Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class Estado extends TRecord
{
    const TABLENAME = 'public.estado';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $pais;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('pais_id');
    }

    
    /**
     * Method set_pais
     * Sample of usage: $estado->pais = $object;
     * @param $object Instance of Pais
     */
    public function set_pais(Pais $object)
    {
        $this->pais = $object;
        $this->pais_id = $object->id;
    }
    
    /**
     * Method get_pais
     * Sample of usage: $estado->pais->attribute;
     * @returns Pais instance
     */
    public function get_pais()
    {
        // loads the associated object
        if (empty($this->pais))
            $this->pais = new Pais($this->pais_id);
    
        // returns the associated object
        return $this->pais;
    }
    


}
