<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm( 'Pedido',580,850 );
$frm->setFlat(TRUE);
$frm->setMaximize(TRUE);

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );  // coluna chave da tabela

//$frm->addGroupField('gpx1','Cliente');
	//$g->setColumns('90,100');
	$frm->setColumns('85,500,70,80');
	// Inicio campo AutoComplete
	$frm->addTextField('NMCLIENTE','Cliente:',80,TRUE,80,null,TRUE); //campo obrigatorio para funcionar o autocomplete
	$frm->addTextField('IDCLIENTE','Cód. cliente:',10,TRUE,10,null,false);  //campo obrigatorio para funcionar o autocomplete
	$frm->setAutoComplete('NMCLIENTE','vw_cliente','NMCLIENTE','IDCLIENTE|IDCLIENTE,NMCLIENTE|NMCLIENTE'
						  ,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
	// Fim campo AutoComplete
//$frm->closeGroup();

//$g = $frm->addGroupField('gpx2','Outras informações');
////$frm->addGroupField('gpx2', 'Outras informações');

	$frm->addDateField('DTPEDIDO', 'Data:',TRUE);
	$frm->addNumberField('VLTOTAL', 'Valor total:',10,FALSE,2)->setEnabled( FALSE );
	$frm->addNumberField('VLDESCONTO', 'Valor desconto:',10,FALSE,2);
	$frm->addNumberField('VLPAGO', 'Valor pago:',10,FALSE,2)->setEnabled( FALSE );
//$frm->closeGroup();


$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
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
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Lista de pedidos' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'pedido.php' );

	$gride->addColumn($primaryKey,'Pedido');
	$gride->addColumnCompact('NMCLIENTE','Cliente');
	$gride->addColumn('DTPEDIDO','Data');
	$gride->addColumn('VLTOTAL','Valor total');
	$gride->addColumn('VLDESCONTO','Valor desconto');
	$gride->addColumn('VLPAGO','Valor Pago');

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
					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>