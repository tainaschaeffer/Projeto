<?php
/**
 * Pessoa Active Record
 * @author  Tainá Schaeffer 18-06-2018
 */
class Pessoa extends TRecord
{
    const TABLENAME = 'public.pessoa';
    const PRIMARYKEY= 'id';
    const IDPOLICY =  'serial'; // {max, serial}
    
    
    private $nacionalidade;
    private $estado_civil;
    private $sexo;
    private $tipo_pessoa;

    /**
     * Constructor method
     */
    public function __construct($id = NULL, $callObjectLoad = TRUE)
    {
        parent::__construct($id, $callObjectLoad);
        parent::addAttribute('nome');
        parent::addAttribute('apelido');
        parent::addAttribute('data_nascimento');
        parent::addAttribute('razao_social');
        parent::addAttribute('cpf');
        parent::addAttribute('cnpj');
        parent::addAttribute('rg');
        parent::addAttribute('rg_emissor');
        parent::addAttribute('rg_estado');
        parent::addAttribute('nacionalidade_id');
        parent::addAttribute('estado_civil_id');
        parent::addAttribute('sexo_id');
        parent::addAttribute('tipo_pessoa_id');
    }

    
    /**
     * Method set_nacionalidade
     * Sample of usage: $pessoa->nacionalidade = $object;
     * @param $object Instance of Nacionalidade
     */
    public function set_nacionalidade(Nacionalidade $object)
    {
        $this->nacionalidade = $object;
        $this->nacionalidade_id = $object->id;
    }
    
    /**
     * Method get_nacionalidade
     * Sample of usage: $pessoa->nacionalidade->attribute;
     * @returns Nacionalidade instance
     */
    public function get_nacionalidade()
    {
        // loads the associated object
        if (empty($this->nacionalidade))
            $this->nacionalidade = new Nacionalidade($this->nacionalidade_id);
    
        // returns the associated object
        return $this->nacionalidade;
    }
    
    public function get_nacionalidade_nome()
    {
        // loads the associated object
        if (empty($this->nacionalidade))
            $this->nacionalidade = new Nacionalidade($this->nacionalidade_id);
    
        // returns the associated object
        return $this->nacionalidade->nome;
    }
    
    /**
     * Method set_estado_civil
     * Sample of usage: $pessoa->estado_civil = $object;
     * @param $object Instance of EstadoCivil
     */
    public function set_estado_civil(EstadoCivil $object)
    {
        $this->estado_civil = $object;
        $this->estado_civil_id = $object->id;
    }
    
    /**
     * Method get_estado_civil
     * Sample of usage: $pessoa->estado_civil->attribute;
     * @returns EstadoCivil instance
     */
    public function get_estado_civil()
    {
        // loads the associated object
        if (empty($this->estado_civil))
            $this->estado_civil = new EstadoCivil($this->estado_civil_id);
    
        // returns the associated object
        return $this->estado_civil;
    }
    
    public function get_estado_civil_nome()
    {
        // loads the associated object
        if (empty($this->estado_civil))
            $this->estado_civil = new EstadoCivil($this->estado_civil_id);
    
        // returns the associated object
        return $this->estado_civil->nome;
    }

    /**
     * Method set_sexo
     * Sample of usage: $pessoa->sexo = $object;
     * @param $object Instance of Sexo
     */
    public function set_sexo(Sexo $object)
    {
        $this->sexo = $object;
        $this->sexo_id = $object->id;
    }
    
    /**
     * Method get_sexo
     * Sample of usage: $pessoa->sexo->attribute;
     * @returns Sexo instance
     */
    public function get_sexo()
    {
        // loads the associated object
        if (empty($this->sexo))
            $this->sexo = new Sexo($this->sexo_id);
    
        // returns the associated object
        return $this->sexo;
    }
    
    public function get_sexo_nome()
    {
        // loads the associated object
        if (empty($this->sexo))
            $this->sexo = new Sexo($this->sexo_id);
    
        // returns the associated object
        return $this->sexo->nome;
    }

    /**
     * Method set_tipo_pessoa
     * Sample of usage: $pessoa->tipo_pessoa = $object;
     * @param $object Instance of TipoPessoa
     */
    public function set_tipo_pessoa(TipoPessoa $object)
    {
        $this->tipo_pessoa = $object;
        $this->tipo_pessoa_id = $object->id;
    }
    
    /**
     * Method get_tipo_pessoa
     * Sample of usage: $pessoa->tipo_pessoa->attribute;
     * @returns TipoPessoa instance
     */
    public function get_tipo_pessoa()
    {
        // loads the associated object
        if (empty($this->tipo_pessoa))
            $this->tipo_pessoa = new TipoPessoa($this->tipo_pessoa_id);
    
        // returns the associated object
        return $this->tipo_pessoa;
    }
    
    public function get_tipo_pessoa_nome()
    {
        // loads the associated object
        if (empty($this->tipo_pessoa))
            $this->tipo_pessoa = new TipoPessoa($this->tipo_pessoa_id);
    
        // returns the associated object
        return $this->tipo_pessoa->nome;
    }

}
