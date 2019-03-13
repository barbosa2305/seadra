<?php

class ImportaDados {

    const TIPO_ARQUIVO_CLIENTE = 'C';
    const TIPO_ARQUIVO_PRODUTO = 'P';

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
	public static function importa( $tipo,$infoArquivo ){
		$result = null;
        $arquivo = $infoArquivo['arquivo_temp_name'];
        if ( $tipo == self::TIPO_ARQUIVO_CLIENTE ) {
            self::importaCliente( $arquivo );
        } elseif ( $tipo == self::TIPO_ARQUIVO_PRODUTO ) {
            self::importaProduto( $arquivo );
        }
        self::apagaArquivo( $arquivo );
		return $result;
    }  
    //--------------------------------------------------------------------------------
	private static function importaCliente( $arquivo ){
		$result = null;
		if ( ($objeto = fopen($arquivo,'r')) !== FALSE ) {
            $linha = 1;
            while( ($dados = fgetcsv($objeto,1000,';')) !== FALSE ) {
                /*
                $num = count($dados);
                echo "<p> $num campos na linha $linha: <br /></p>\n";
                */
                $linha++;
                /*
                for ($c=0; $c < $num; $c++) {
                    echo utf8_encode($dados[$c]) . "<br />\n";
                }
                */

                //$nome = utf8_encode($linha[0]);
            }
            fclose($objeto);
        }  

		$result = TRUE;
		return $result;
    }  
    //--------------------------------------------------------------------------------
	private static function importaProduto( $arquivo ){
		$result = null;
    

		$result = TRUE;
		return $result;
    } 
    //--------------------------------------------------------------------------------
	private static function apagaArquivo( $arquivo ){
		if ( file_exists($arquivo) ) unlink( $arquivo );
    }  
    //--------------------------------------------------------------------------------
}
?>