<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUSUARIO';

$frm = new TForm('Usuário',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);

$frm->addHiddenField( 'BUSCAR' ); // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // Coluna chave da tabela

$g = $frm->addGroupField('gpx1');
	$g->setColumns('60,80');

	$frm->addTextField('NMUSUARIO','Nome:',255,TRUE,80);
	$frm->addTextField('DSLOGIN','Usuário:',20,TRUE,20);
	$grupo = array('U' => 'Usuários', 'A' => 'Administradores');
	$frm->addSelectField('TPGRUPO', 'Grupo:',true,$grupo,null,null,null,null,null,null,' ',null);
	$ativo = array('S' => 'Sim', 'N' => 'Não');
	$frm->addSelectField('STATIVO', 'Ativo ?',true,$ativo,null,null,null,null,null,null,' ',null);
$g->closeGroup();

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try {
			if ( $frm->validate() ) {
				$vo = new UsuarioVO();
				$frm->setVo( $vo );
				$resultado = Usuario::save( $vo );
				if ($resultado==1) {
					if ( empty($frm->get('IDUSUARIO')) ) {
						$frm->setMessage('Operação realizada com sucesso! \r\n Usuário cadastrado com a senha padrão: 12345678');
					} else {
						$frm->setMessage('Operação realizada com sucesso!');
					}
					$frm->clearFields();
				} else {
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
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		try {
			$vo = new UsuarioVO();
			$frm->setVo( $vo );
			$resultado = Usuario::saveStatus( $vo );
			if ($resultado==1) {
				$frm->setMessage('Operação realizada com sucesso!');
				$frm->clearFields();
			} else {
				$frm->clearFields();
				$frm->setMessage($resultado);
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
	case 'redefinirSenha':
		try {
			$vo = new UsuarioVO();
			$frm->setVo( $vo );
			$result = Usuario::changePassword(true, $vo);
			if ($result==1) {
				$frm->setMessage('Operação realizada com sucesso!');
				$frm->clearFields();
			} else {
				$frm->setMessage($result);
				$frm->clearFields();
			}
		}
		catch (DomainException $e) {
			$frm->setMessage( $e->getMessage() );
		}
		catch (Exception $e) {
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

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Usuario::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Usuario::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',NMUSUARIO|NMUSUARIO'
					.',DSLOGIN|DSLOGIN'
					.',TPGRUPO|TPGRUPO'
					.',STATIVO|STATIVO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Lista de usuários' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'usuario.php' );

	$gride->addColumn($primaryKey,'Código');
	$gride->addColumn('NMUSUARIO','Nome');
	$gride->addColumn('DSLOGIN','Usuário');
	$gride->addColumn('TPGRUPO','Grupo');
	$gride->addColumn('STATIVO','Ativo ?');

	$gride->addButton('Alterar','gd_alterar',null,null,null,'alterar.gif');
	$gride->addButton('Excluir','gd_excluir',null,null,'Deseja exlcuir o registro?','lixeira.gif');
	$gride->addButton('Redefinir senha','redefinirSenha',null,null,'Confirma redefinir a senha? \r\n Será alterada para senha padrão: 12345678','access16.gif');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->show();

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