<?php
defined('APLICATIVO') or die();

$frm = new TForm('Importar dados', 250, 550);

$fileFormat = 'csv';
$frm->setColumns('100,300');
$frm->addRadioField('TIPODADO','Dados de: ',TRUE,'C=Clientes,P=Produtos',null,FALSE,null,null,null,null,null,FALSE);
$frm->addHtmlField('html1', 'Arquivo de tamanho maximo de 2MB e no formato: '.$fileFormat, null, 'Dica:', null, 200)->setCss('border', '1px dashed blue');
$frm->addFileField('ANEXO','Arquivo: ',TRUE,$fileFormat,'2M',40,FALSE);

$frm->addButton('Importar', null, 'Importar', null, null, TRUE, TRUE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, TRUE);

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
                $tipo = $frm->get('TIPODADO');
                $dados = $frm->createBvars('ANEXO');
                $resultado = ImportaDados::importa( $_FILES,$tipo,$dados );
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