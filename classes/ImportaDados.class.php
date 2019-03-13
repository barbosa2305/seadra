<?php

class ImportaDados {

	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function converteMoeda( $valor ){
		$result = null;
		if ( !empty($valor) ){
			$result = str_replace( array(".", ","), array(",", "."), $valor );
		}
		return $result;
	}
  //--------------------------------------------------------------------------------
	public static function importa( $infoArquivo,$tipo,$dados ){
		$result = null;
		//d($tipo);
		//d($dados);
		//d($infoArquivo);

		$arquivo = $infoArquivo['ANEXO']['tmp_name'];
		$nome = $infoArquivo['ANEXO']['name'];

		$objeto = fopen($arquivo,"r");

		while( ($linha = fgetcsv($objeto,1000,";")) !== FALSE ) {
			$nome = utf8_encode($linha[0]);
			$teste = utf8_encode($linha[1]);

			echo $nome.'\n';
			echo $teste.'\n';

		}


		$result = TRUE;
		return $result;
    }  
	//--------------------------------------------------------------------------------
}
?>