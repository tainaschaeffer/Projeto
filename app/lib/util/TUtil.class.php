<?php
/**
 * EnderecoFormList Form List
 * @author  Tainá Schaeffer 18-06-2018
 */
class TUtil extends TRecord
{
	/*
	Função para remover caracteres de CPF ou CNPJ
	*/
	public static function limpaCPF_CNPJ($valor)
	{
		$valor = trim($valor);
	 	$valor = str_replace(".", "", $valor);
	 	$valor = str_replace(",", "", $valor);
	 	$valor = str_replace("-", "", $valor);
	 	$valor = str_replace("/", "", $valor);
	 	return $valor;
	}

	/*
	Função para adicionar formatação de CNPJ
	*/
	public static function adicionaMascaraCNPJ($cnpj)
	{
		$parte_um     = substr($cnpj, 0, 2);
		$parte_dois   = substr($cnpj, 2, 3);
		$parte_tres   = substr($cnpj, 5, 3);
		$parte_quatro = substr($cnpj, 8, 4);
		$parte_cinco  = substr($cnpj, 12, 2);

		$monta_cnpj = "$parte_um.$parte_dois.$parte_tres/$parte_quatro-$parte_cinco";

		return $monta_cnpj;
	}

	/*
	Função para adicionar formatação de CPF
	*/
	public static function adicionaMascaraCPF($cpf)
	{
		$parte_um     = substr($cpf, 0, 3);
		$parte_dois   = substr($cpf, 3, 3);
		$parte_tres   = substr($cpf, 6, 3);
		$parte_quatro = substr($cpf, 9, 2);

		$monta_cpf = "$parte_um.$parte_dois.$parte_tres-$parte_quatro";

		return $monta_cpf;
	}
}