<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUSUARIO';
$frm = new TForm('Alterar a senha',190,400);
$frm->setFlat(true);
$frm->setMaximize(true);
$frm->setColumns('110');

$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$user = ArrayHelper::get($_SESSION[APLICATIVO],'USER');
$frm->addTextField('login','Usuário',255,true,35,$user['LOGIN'])->setEnabled(false)->setAttribute('align','center');
$frm->addPasswordField('senha','Senha atual',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addHtmlField('espaço1','');
$frm->addPasswordField('senha1','Nova senha',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addPasswordField('senha2','Repita a nova senha',true,null,20,null,null,null,35)->setAttribute('align','center');
$frm->addHtmlField('espaço2','');
$frm->addButton('Salvar', null, 'Salvar', null, null, true, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
			    $login = $frm->getFieldValue('login');
			    $senhaAtual = $frm->getFieldValue('senha');
				$senha1 = $frm->getFieldValue('senha1');
				$senha2 = $frm->getFieldValue('senha2');
				$resultado = Acesso::changeNewPassword($login, $senhaAtual, $senha1, $senha2);
				if($resultado==1) {
					$frm->setMessage('Registro gravado com sucesso!!!');
                    $frm->clearFields(null,array('login'));
				}else{
					$frm->setMessage($resultado);
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