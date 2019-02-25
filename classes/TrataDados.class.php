<?php

class TrataDados {

	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	/**
	 * Converte um valor resumido para um valor detalhado.
	 * Ex.: De 'S' para 'Sim', de 'N' para 'Não'.
	 **/
	public static function converte( $dados ){
		if( !empty( $dados['STATIVO'] ) ){
			foreach ($dados['STATIVO'] as $key => $value) {
				$dsPrincipal = 'Erro';
				if( $value == 'S' ){
					$dsPrincipal = 'Sim';
				} else {
					$dsPrincipal = 'Não';
				}
				$dados['DSATIVO'][$key]  = $dsPrincipal;
			}
		}
		if( !empty( $dados['TPGRUPO'] ) ){
			foreach ($dados['TPGRUPO'] as $key => $value) {
				$dsPrincipal = 'Erro';
				if( $value == 'A' ){
					$dsPrincipal = 'Administradores';
				} else {
					$dsPrincipal = 'Usuários';
				}
				$dados['DSGRUPO'][$key]  = $dsPrincipal;
			}
		}
	    return $dados;
	}
	//--------------------------------------------------------------------------------
	/**
	 * Converte um valor moeda (pt_BR) para o valor em formato americano.
	 **/
	public static function converteMoeda( $valor ){
		$result = null;
		if ( !empty($valor) && floatval($valor) > 0 ){
			$result = str_replace( array(".", ","), array(",", "."), $valor );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
}
?>