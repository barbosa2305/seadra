<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUSUARIO';

$frm = new TForm('Alterar a senha',190,400);
$frm->setFlat(true);
$frm->setMaximize(true);
$frm->setColumns('110');

$frm->addHiddenField( 'BUSCAR' ); // campo oculto para buscas
$frm->addHiddenField( $primaryKey ); // coluna chave da tabela

$userLogin = Acesso::getUserLogin();
$frm->addTextField('login','Usuário',255,true,35,$userLogin)->setEnabled(false)->setAttribute('align','center');
$frm->addPasswordField('senha','Senha atual',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addHtmlField('espaço1','');
$frm->addPasswordField('novaSenha','Nova senha',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addPasswordField('novaSenhaRepita','Repita a nova senha',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addHtmlField('espaço2','');

$frm->addButton('Salvar', null, 'Salvar', null, null, true, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new UsuarioVO();
				$vo->setIdusuario(Acesso::getUserId());
				$vo->setDslogin($frm->getFieldValue('login'));
				//$userId = Acesso::getUserId();
				//$userLogin = $frm->getFieldValue('login');
			    $senhaAtual = $frm->getFieldValue('senha');
				$novaSenha = $frm->getFieldValue('novaSenha');
				$novaSenhaRepita = $frm->getFieldValue('novaSenhaRepita');
				$result = Usuario::changePassword(false, $vo, $senhaAtual, $novaSenha, $novaSenhaRepita);
				if ( $result == 1 ) {
					$frm->setMessage(Mensagem::REGISTRO_GRAVADO);
                    $frm->clearFields(null, array('login'));
				} else {
					$frm->setMessage($result);
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
	//--------------------------------------------------------------------------------
	case 'Limpar':
        $frm->clearFields(null,array('login'));
        $frm->setFocusField('senha');
	break;
}

$frm->show();

?>