<?php
/**
 * EnderecoFormList Form List
 * @author  Tainá Schaeffer 18-06-2018
 */
class TUtil extends TRecord
{
	function limpaCPF_CNPJ($valor){
	 $valor = trim($valor);
	 $valor = str_replace(".", "", $valor);
	 $valor = str_replace(",", "", $valor);
	 $valor = str_replace("-", "", $valor);
	 $valor = str_replace("/", "", $valor);
	 return $valor;
	}
}