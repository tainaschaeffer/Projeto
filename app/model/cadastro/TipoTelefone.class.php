<?php
/**
 * TipoTelefone Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class TipoTelefone extends TRecord
{
    const TABLENAME = 'public.tipo_telefone';
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
