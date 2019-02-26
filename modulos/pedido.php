<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm( 'Pedido',580,850 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );  // coluna chave da tabela

$g = $frm->addGroupField('gpx1');
	$g->setColumns('80,110,85');
	// Inicio campo AutoComplete
	$frm->addTextField('IDCLIENTE','Cliente:',13,TRUE,13,null,TRUE);  //campo obrigatorio para funcionar o autocomplete
	$frm->addTextField('NMCLIENTE',null,85,FALSE,85,null,FALSE); //campo obrigatorio para funcionar o autocomplete
	$frm->setAutoComplete('NMCLIENTE','vw_cliente','NMCLIENTE','IDCLIENTE|IDCLIENTE,NMCLIENTE|NMCLIENTE'
						  ,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
	// Fim campo AutoComplete
	$frm->addDateField('DTPEDIDO','Data:',TRUE,null);
	$frm->addNumberField('VLDESCONTO', 'Desconto (R$):',10,FALSE,2,TRUE);
	$frm->addHtmlField('html1', '<br>* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');
$g->closeGroup();

$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
$frm->addButton('Limpar',null,'Limpar',null,null,FALSE,FALSE);
$frm->addButton('Ir para cliente','Cliente','btnCliente',null,null,FALSE,TRUE);

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
	/*
	case 'gd_imprimir':
		try {
			
			$_SESSION[APLICATIVO]['RELATORIO'] = null;
			$_SESSION[APLICATIVO]['RELATORIO']['IDPEDIDO'] = $frm->getFieldValue('IDPEDIDO');
			$_SESSION[APLICATIVO]['RELATORIO']['NOM_PESSOA'] = $frm->getFieldValue('NOM_PESSOA');
			$_SESSION[APLICATIVO]['RELATORIO']['DAT_PEDIDO'] = $frm->getFieldValue('DAT_PEDIDO');
			$frm->redirect('relatorio.php');
			
			
		}
		catch ( DomainException $e ){
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ){
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	*/
	//--------------------------------------------------------------------------------
	case 'Cliente':
		$frm->setFieldValue( 'BUSCAR',null );
		$frm->setFieldValue( 'IDCLIENTE',null );
		$frm->setFieldValue( 'NMCLIENTE',null );
		$frm->redirect( 'cliente.php',null,TRUE );
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
				,'VLDESCONTO'=>TrataDados::converteMoeda( $frm->get('VLDESCONTO') )
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
					.',VLDESCONTO|VLDESCONTO'
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
	$gride->addColumn('VLTOTAL','Valor total (R$)');
	$gride->addColumn('VLDESCONTO','Valor desconto (R$)');
	$gride->addColumn('VLPAGO','Valor pago (R$)');

	$gride->addButton('Adicionar itens no pedido','gd_itens','btnItens',null,null,'images/gtk_add_17px.png');
	$gride->addButton('Gerar orçamento',null,'btnImprimir','exibir_pdf()',null,'print16.gif');
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
					,"NMCLIENTE":""
					,"DTPEDIDO":""
					,"VLDESCONTO":""
					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
function exibir_pdf() {
	fwShowPdf({"modulo":"relatorios/rel_orcamento.php","rel_idpedido":jQuery("#IDPEDIDO").val()});
}
</script>
