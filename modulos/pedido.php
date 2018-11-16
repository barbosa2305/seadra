<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm('pedido',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$listCliente = Cliente::selectAll();
$frm->addSelectField('IDCLIENTE', 'IDCLIENTE',TRUE,$listCliente,null,null,null,null,null,null,' ',null);
$frm->addDateField('DTPEDIDO', 'DTPEDIDO',TRUE);
$frm->addNumberField('VLTOTAL', 'VLTOTAL',10,FALSE,2);
$frm->addNumberField('VLDESCONTO', 'VLDESCONTO',10,FALSE,2);
$frm->addNumberField('VLPAGO', 'VLPAGO',10,FALSE,2);
$listUsuario = Usuario::selectAll();
$frm->addSelectField('IDUSUARIOCRIACAO', 'IDUSUARIOCRIACAO',TRUE,$listUsuario,null,null,null,null,null,null,' ',null);
$frm->addDateField('DTCRIACAO', 'DTCRIACAO',TRUE);
$listUsuario = Usuario::selectAll();
$frm->addSelectField('IDUSUARIOMODIFICACAO', 'IDUSUARIOMODIFICACAO',FALSE,$listUsuario,null,null,null,null,null,null,' ',null);
$frm->addDateField('DTMODIFICACAO', 'DTMODIFICACAO',FALSE);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new PedidoVO();
				$frm->setVo( $vo );
				$resultado = Pedido::save( $vo );
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
			$resultado = Pedido::delete( $id );;
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
				'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDCLIENTE'=>$frm->get('IDCLIENTE')
				,'DTPEDIDO'=>$frm->get('DTPEDIDO')
				,'VLTOTAL'=>$frm->get('VLTOTAL')
				,'VLDESCONTO'=>$frm->get('VLDESCONTO')
				,'VLPAGO'=>$frm->get('VLPAGO')
				,'IDUSUARIOCRIACAO'=>$frm->get('IDUSUARIOCRIACAO')
				,'DTCRIACAO'=>$frm->get('DTCRIACAO')
				,'IDUSUARIOMODIFICACAO'=>$frm->get('IDUSUARIOMODIFICACAO')
				,'DTMODIFICACAO'=>$frm->get('DTMODIFICACAO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Pedido::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Pedido::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',IDCLIENTE|IDCLIENTE'
					.',DTPEDIDO|DTPEDIDO'
					.',VLTOTAL|VLTOTAL'
					.',VLDESCONTO|VLDESCONTO'
					.',VLPAGO|VLPAGO'
					.',IDUSUARIOCRIACAO|IDUSUARIOCRIACAO'
					.',DTCRIACAO|DTCRIACAO'
					.',IDUSUARIOMODIFICACAO|IDUSUARIOMODIFICACAO'
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
	$gride->setUrl( 'pedido.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('IDCLIENTE','IDCLIENTE');
	$gride->addColumn('DTPEDIDO','DTPEDIDO');
	$gride->addColumn('VLTOTAL','VLTOTAL');
	$gride->addColumn('VLDESCONTO','VLDESCONTO');
	$gride->addColumn('VLPAGO','VLPAGO');
	$gride->addColumn('IDUSUARIOCRIACAO','IDUSUARIOCRIACAO');
	$gride->addColumn('DTCRIACAO','DTCRIACAO');
	$gride->addColumn('IDUSUARIOMODIFICACAO','IDUSUARIOMODIFICACAO');
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
					,"IDPEDIDO":""
					,"IDCLIENTE":""
					,"DTPEDIDO":""
					,"VLTOTAL":""
					,"VLDESCONTO":""
					,"VLPAGO":""
					,"IDUSUARIOCRIACAO":""
					,"DTCRIACAO":""
					,"IDUSUARIOMODIFICACAO":""
					,"DTMODIFICACAO":""
					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>