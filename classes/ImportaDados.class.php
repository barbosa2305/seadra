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
            $result = self::importaCliente( $arquivo );
        } elseif ( $tipo == self::TIPO_ARQUIVO_PRODUTO ) {
            $result = self::importaProduto( $arquivo );
        }
        self::apagaArquivo( $arquivo );
		return $result;
    }  
    //--------------------------------------------------------------------------------
	private static function importaCliente( $arquivo ){
        $result = TRUE;
		if ( ($objeto = fopen($arquivo,'r')) !== FALSE ){
            $linha = 1;
            while( ($dados = fgetcsv($objeto,1000,';')) !== FALSE ){ 
                $voCliente = new ClienteVO();
                $num = count($dados);
                for ($c=0; $c < $num; $c++){                   
                    $valor = utf8_encode(trim($dados[$c]));
                    $campo = strtolower(strstr($valor, ':', TRUE));

                    switch( $campo ){
                        case 'nome':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setNmcliente( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'cpf':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $nrCpfCnpj = preg_replace('/[^0-9]/','',trim($dados[$c+1]));
                                $voCliente->setNrcpfcnpj( utf8_encode($nrCpfCnpj) );
                                $c++;
                            }
                        break;
                        case 'cnpj':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $nrCpfCnpj = preg_replace('/[^0-9]/','',trim($dados[$c+1]));
                                $voCliente->setNrcpfcnpj( utf8_encode($nrCpfCnpj) );
                                $c++;
                            }
                        break;
                        case 'e-mail':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setDsemail( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'celular':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $nrCelular = preg_replace('/[^0-9]/','',trim($dados[$c+1]));
                                $nrCelular = preg_replace('/^0+/','', $nrCelular);
                                $voCliente->setNrcelular( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'telefone':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $nrTelefone = preg_replace('/[^0-9]/','',trim($dados[$c+1]));
                                $nrTelefone = preg_replace('/^0+/','', $nrTelefone);
                                $voCliente->setNrtelefone( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'endereço':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setDslogradouro( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                                $c++;
                                $valor = utf8_encode(trim($dados[$c]));
                                $campo = strtolower(strstr($valor, ':', TRUE));
                                if ($campo === 'nº'){
                                    if (!strstr(trim($dados[$c+1]), ':')){
                                        $logradouro = utf8_encode(trim($voCliente->getDslogradouro().', '.$dados[$c+1]));
                                        $voCliente->setDslogradouro( $logradouro );
                                        $c++;
                                    }
                                }
                            }
                        break;
                        case 'município': 
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $nmMunicipio = utf8_encode(trim($dados[$c+1]));
                                $c++;
                                $c++;
                                $valor = utf8_encode(trim($dados[$c]));
                                $campo = strtolower(strstr($valor, ':', TRUE));
                                if ($campo === 'uf'){
                                    if (!strstr(trim($dados[$c+1]), ':')){
                                        $dsSigla = utf8_encode(trim($dados[$c+1]));
                                        $dadosMunicipio = MunicipioDAO::selectByMunicipioSigla( $nmMunicipio,$dsSigla );
                                        $idMunicipio =  $dadosMunicipio['IDMUNICIPIO'][0];
                                        $voCliente->setIdmunicipio( $idMunicipio );
                                        $voCliente->setDslocalidade( $nmMunicipio );
                                        $c++;
                                    }
                                }
                            }
                        break;
                        case 'bairro':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setDsbairro( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'complemento':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setDscomplemento( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                        case 'cep':
                            if (!strstr(trim($dados[$c+1]), ':')){
                                $voCliente->setDscep( utf8_encode(trim($dados[$c+1])) );
                                $c++;
                            }
                        break;
                    }
                }
                try {
                    $voCliente->setIdusuario( Acesso::getUserId() );
                    Cliente::save( $voCliente );
                } catch ( Exception $e ) {
                    MessageHelper::logRecord( $e );
                    $result = FALSE;
                }     
                $linha++;
            }
            fclose($objeto);
        }  
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