<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm( 'Pedido',580,850 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );  // coluna chave da tabela

$frm->setColumns('82,500,68,80');
// Inicio campo AutoComplete
$frm->addTextField('IDCLIENTE','Cód. cliente:',10,TRUE,10,null,TRUE);  //campo obrigatorio para funcionar o autocomplete
$frm->addTextField('NMCLIENTE','Cliente:',80,TRUE,80,null,TRUE); //campo obrigatorio para funcionar o autocomplete
$frm->setAutoComplete('NMCLIENTE','vw_cliente','NMCLIENTE','IDCLIENTE|IDCLIENTE,NMCLIENTE|NMCLIENTE'
					  ,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
// Fim campo AutoComplete
$frm->addDateField('DTPEDIDO','Data:',TRUE);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);
$frm->addButton('Cliente', null, 'btnCliente', null, null, FALSE, FALSE);
$frm->addButton('Produto', null, 'btnProduto', null, null, FALSE, FALSE);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'Salvar':
		try {
			if ( $frm->validate() ){
				$vo = new PedidoVO();
				$frm->setVo( $vo );
				$vo->setIdusuario( Acesso::getUserId() );
				$resultado = Pedido::save( $vo );
				if ( $resultado == 1 ){
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					$frm->clearFields();
				} else {
					$frm->setMessage( $resultado );
				}
			}
		}
		catch ( DomainException $e ){
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ){
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'gd_excluir':
		try{
			$id = $frm->get( $primaryKey ) ;
			$resultado = Pedido::delete( $id );
			if ( $resultado == 1 ){
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->clearFields();
				$frm->setMessage( $resultado );
			}
		}
		catch ( DomainException $e ){
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ){
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'gd_itens':
		try {
			$frm->redirect('itempedido.php');
		}
		catch ( DomainException $e ){
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ){
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'gd_imprimir':
		try {
			/*
			$_SESSION[APLICATIVO]['RELATORIO'] = null;
			$_SESSION[APLICATIVO]['RELATORIO']['IDPEDIDO'] = $frm->getFieldValue('IDPEDIDO');
			$_SESSION[APLICATIVO]['RELATORIO']['NOM_PESSOA'] = $frm->getFieldValue('NOM_PESSOA');
			$_SESSION[APLICATIVO]['RELATORIO']['DAT_PEDIDO'] = $frm->getFieldValue('DAT_PEDIDO');
			$frm->redirect('relatorio.php');
			*/
		}
		catch ( DomainException $e ){
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ){
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'Cliente':
		$frm->redirect( 'cliente.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
	case 'Produto':
		$frm->redirect( 'produto.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
}

function getWhereGridParametersPedido( &$frm ){
	$retorno = null;
	if ( $frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDCLIENTE'=>$frm->get('IDCLIENTE')
				,'DTPEDIDO'=>$frm->get('DTPEDIDO')
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] ) && $_REQUEST['ajax'] ){
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParametersPedido( $frm );
	$page = PostHelper::get('page');
	$dados = Pedido::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
	$realTotalRowsSqlPaginator = Pedido::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',IDCLIENTE|IDCLIENTE'
					.',NMCLIENTE|NMCLIENTE'
					.',DTPEDIDO|DTPEDIDO'
					;
	$gride = new TGrid( 'gd'                // id do gride
					   ,'Lista de pedidos' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields( $mixUpdateFields );
	$gride->setUrl( 'pedido.php' );

	$gride->addColumn($primaryKey,'Pedido');
	$gride->addColumnCompact('NMCLIENTE','Cliente');
	$gride->addColumn('NRCPFCNPJ','CPF/CNPJ');
	$gride->addColumn('DTPEDIDO','Data');
	$gride->addColumn('VLTOTAL','Valor total');
	$gride->addColumn('VLDESCONTO','Valor desconto');
	$gride->addColumn('VLPAGO','Valor pago');

	$gride->addButton('Adicionar itens no pedido','gd_itens','btnItens',null,null,'images/gtk_add_17px.png');
	$gride->addButton('Gerar orçamento','gd_imprimir','btnImprimir',null,null,'print16.gif');
	$gride->addButton('Alterar','gd_alterar','btnAlterar',null,null,'alterar.gif');
	$gride->addButton('Excluir','gd_excluir','btnExcluir',null,'Confirma a exclusão do pedido?','lixeira.gif');

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
					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>