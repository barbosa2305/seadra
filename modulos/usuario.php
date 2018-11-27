<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUSUARIO';
$frm = new TForm('usuario',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addMemoField('NMUSUARIO', 'NMUSUARIO',255,TRUE,80,3);
$frm->addTextField('DSLOGIN', 'DSLOGIN',20,TRUE,20);
$frm->addMemoField('DSSENHA', 'DSSENHA',255,TRUE,80,3);
$frm->addTextField('STATIVO', 'STATIVO',1,TRUE,1);
$frm->addDateField('DTCRIACAO', 'DTCRIACAO',TRUE);
$frm->addDateField('DTMODIFICACAO', 'DTMODIFICACAO',FALSE);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new UsuarioVO();
				$frm->setVo( $vo );
				$resultado = Usuario::save( $vo );
				if($resultado==1) {
					$frm->setMessage('Registro gravado com sucesso!!!');
					$frm->clearFields();
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
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		try{
			$id = $frm->get( $primaryKey ) ;
			$resultado = Usuario::delete( $id );;
			if($resultado==1) {
				$frm->setMessage('Registro excluido com sucesso!!!');
				$frm->clearFields();
			}else{
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
}


function getWhereGridParameters(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDUSUARIO'=>$frm->get('IDUSUARIO')
				,'NMUSUARIO'=>$frm->get('NMUSUARIO')
				,'DSLOGIN'=>$frm->get('DSLOGIN')
				,'DSSENHA'=>$frm->get('DSSENHA')
				,'STATIVO'=>$frm->get('STATIVO')
				,'DTCRIACAO'=>$frm->get('DTCRIACAO')
				,'DTMODIFICACAO'=>$frm->get('DTMODIFICACAO')
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
					.',DSSENHA|DSSENHA'
					.',STATIVO|STATIVO'
					.',DTCRIACAO|DTCRIACAO'
					.',DTMODIFICACAO|DTMODIFICACAO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Gride with SQL Pagination' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'usuario.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('NMUSUARIO','NMUSUARIO');
	$gride->addColumn('DSLOGIN','DSLOGIN');
	$gride->addColumn('DSSENHA','DSSENHA');
	$gride->addColumn('STATIVO','STATIVO');
	$gride->addColumn('DTCRIACAO','DTCRIACAO');
	$gride->addColumn('DTMODIFICACAO','DTMODIFICACAO');

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
					,"DSSENHA":""
					,"STATIVO":""
					,"DTCRIACAO":""
					,"DTMODIFICACAO":""
					};
	fwGetGrid('usuario.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>