<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUSUARIO';

$frm = new TForm('Usuário',560,840);
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' ); // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // Coluna chave da tabela

$frm->setColumns('38,40,44,40,39,40,38');
$frm->addGroupField('gpx1');
	$frm->addTextField('NMUSUARIO','Nome:',255,TRUE,55);
	$frm->addTextField('DSLOGIN','Usuário:',20,TRUE,16,null,FALSE);
	$grupo = array('U' => 'Usuários', 'A' => 'Administradores');
	$frm->addSelectField('TPGRUPO', 'Grupo:',TRUE,$grupo,FALSE,null,null,null,null,null,null,'U');
	$ativo = array('S' => 'Sim', 'N' => 'Não');
	$frm->addSelectField('STATIVO', 'Ativo ?',TRUE,$ativo,FALSE,null,null,null,null,null,null,'S');
$frm->closeGroup();
$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try {
			if ( $frm->validate() ) {
				$vo = new UsuarioVO();
				$frm->setVo( $vo );
				$resultado = Usuario::save( $vo );
				if ( $resultado == 1 ) {
					if ( empty($frm->get('IDUSUARIO')) ) {
						$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO .' \r\n '. Mensagem::SENHA_PADRAO_USUARIO );
					} else {
						$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					}
					$frm->clearFields();
				} else {
					$frm->setMessage( $resultado );
				}
			}
		}
		catch ( DomainException $e ) {
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		try {
			$vo = new UsuarioVO();
			$frm->setVo( $vo );
			$resultado = Usuario::delete( $vo );
			if ( $resultado == 1 ) {
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->clearFields();
				$frm->setMessage( $resultado );
			}
		}
		catch ( DomainException $e ) {
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'gd_redefinirSenha':
		try {
			$vo = new UsuarioVO();
			$frm->setVo( $vo );
			$resultado = Usuario::redefinirSenha( $vo );
			if ( $resultado == 1 ) {
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->setMessage( $resultado );
				$frm->clearFields();
			}
		}
		catch ( DomainException $e ) {
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ) {
			MessageHelper::logRecord($e);
			$frm->setMessage( $e->getMessage() );
		}
}

function getWhereGridParameters(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDUSUARIO'=>$frm->get('IDUSUARIO')
				,'NMUSUARIO'=>$frm->get('NMUSUARIO')
				,'DSLOGIN'=>$frm->get('DSLOGIN')
				,'TPGRUPO'=>$frm->get('TPGRUPO')
				,'STATIVO'=>$frm->get('STATIVO')
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] ) && $_REQUEST['ajax'] ){
	$maxRows = LINHAS_POR_PAGINA;
	$whereGrid = getWhereGridParameters( $frm );
	$page = PostHelper::get('page');
	$dados = Usuario::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
	$realTotalRowsSqlPaginator = Usuario::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',NMUSUARIO|NMUSUARIO'
					.',DSLOGIN|DSLOGIN'
					.',TPGRUPO|TPGRUPO'
					.',STATIVO|STATIVO'
					;
	$tituloGride = 'Lista de usuários -'.' Quantidade: '.$realTotalRowsSqlPaginator;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,$tituloGride // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields( $mixUpdateFields );
	$gride->setUrl( 'usuario.php' );
	$gride->setExportExcel( FALSE );
	$gride->setOnDrawActionButton( 'gdConfigButtons' );
	$gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Código');
	$gride->addColumn('NMUSUARIO','Nome');
	$gride->addColumn('DSLOGIN','Usuário');
	$gride->addColumn('DSGRUPO','Grupo');
	$gride->addColumn('DSATIVO','Ativo ?',null,'center');

	$gride->addButton('Alterar','gd_alterar','btnAlterar',null,null,'alterar.gif');
	$gride->addButton('Excluir','gd_excluir','btnExcluir',null,'Deseja exlcuir o registro?','lixeira.gif');
	$gride->addButton('Redefinir senha','gd_redefinirSenha','btnRedefinir',null,'Confirma redefinir a senha? \r\n Será alterada para senha padrão: 12345678','access16.gif');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->show();

function gdConfigButtons($rowNum, TButton $button, $objColumn, $aData){
	$property = $button->getProperty('name');
    if ( $aData['DSLOGIN'] == Acesso::USER_ADMIN && (Acesso::isUserLoggedAdm()) ){
        if ( $property != 'btnRedefinir' ){
           $button->setVisible(FALSE);
        }
    } elseif ( $aData['DSLOGIN'] == Acesso::USER_ADMIN && !(Acesso::isUserLoggedAdm()) ){
		$button->setVisible(FALSE);
	}
	if ( $aData['DSLOGIN'] == Acesso::getUserLogin() ){
		if ( $property == 'btnExcluir' ){
            $button->setVisible(FALSE);
        }
	}
}

?>
<script>
function init() {
	var Parameters = {"BUSCAR":""
					,"IDUSUARIO":""
					,"NMUSUARIO":""
					,"DSLOGIN":""
					,"TPGRUPO":""
					,"STATIVO":""
					};
	fwGetGrid('usuario.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>