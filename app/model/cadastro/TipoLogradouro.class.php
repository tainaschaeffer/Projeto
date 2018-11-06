<?php
/**
 * TipoLogradouro Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class TipoLogradouro extends TRecord
{
    const TABLENAME = 'public.tipo_logradouro';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
    }


}
