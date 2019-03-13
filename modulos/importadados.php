<?php
defined('APLICATIVO') or die();

$frm = new TForm('Importar dados', 250, 550);

$fileFormat = 'csv';
$frm->setColumns('100,300');
$frm->addRadioField('TIPODADO','Dados de: ',TRUE,'C=Clientes,P=Produtos',null,FALSE,null,null,null,null,null,FALSE);
$frm->addHtmlField('html1', 'Arquivo de tamanho maximo de 5MB e no formato: '.$fileFormat, null, 'Dica:', null, 200)->setCss('border', '1px dashed blue');
$arquivo = 'ARQUIVODADOS';
$frm->addFileField($arquivo,'Arquivo: ',TRUE,$fileFormat,'5M',40,TRUE);

$frm->addButton('Importar', null, 'Importar', null, null, TRUE, TRUE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, TRUE);

function getPostArquivo(&$frm,$arquivo){
    $resultado['arquivo_name']      = PostHelper::get($arquivo);
    $resultado['arquivo_temp_name'] = $frm->getBase().PostHelper::get($arquivo.'_temp_name');
    $resultado['arquivo_extension'] = PostHelper::get($arquivo.'_extension');
    $resultado['arquivo_size']      = PostHelper::get($arquivo.'_size');
    $resultado['arquivo_type']      = PostHelper::get($arquivo.'_type');
    return $resultado;
}

$acao = isset($acao) ? $acao : null;
switch ($acao) {
    //--------------------------------------------------------------------------------
	case 'Limpar':
        $frm->clearFields();
    break;
    //--------------------------------------------------------------------------------
    case 'Importar': {
        try {
			if ( $frm->validate() ){ 
                $postArquivo = getPostArquivo( $frm,$arquivo );
                $tipo = $frm->get('TIPODADO');
                $resultado = ImportaDados::importa( $tipo,$postArquivo );
				if ( $resultado ){
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					$frm->clearFields();
				} else {
					$frm->setMessage( $resultado );
                }
			}
		}
		catch (DomainException $e) {
			$frm->setMessage( $e->getMessage() );
		}
		catch (Exception $e) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
	break;
    }
    //--------------------------------------------------------------------------------
}
$frm->show();