<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPEDIDO';
$frm = new TForm( 'Pedido',630,850 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );  // coluna chave da tabela

$frm->setColumns('42');
$frm->addGroupField('gpx1','Busca por');
	$frm->addNumberField('IDPEDIDOBUSCA','Pedido:',6,FALSE,0,TRUE)->setExampleText('Utilizar este campo apenas para busca.');
$frm->closeGroup();

$frm->setColumns('42,35,40,30,32');
$frm->addGroupField('gpx2','Dados do pedido');
	// Inicio campo AutoComplete
	$frm->addTextField('IDCLIENTE','Cliente:',6,TRUE,6,null,FALSE);  //campo obrigatorio para funcionar o autocomplete
	$frm->addTextField('NMCLIENTE',null,255,FALSE,68,null,FALSE); //campo obrigatorio para funcionar o autocomplete
	$frm->setAutoComplete('NMCLIENTE','vw_cliente_ativo','NMCLIENTE','IDCLIENTE|IDCLIENTE,NMCLIENTE|NMCLIENTE'
						  ,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
	// Fim campo AutoComplete
	$frm->addDateField('DTPEDIDO','Data:',TRUE,FALSE);
$frm->closeGroup();
$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
$frm->addButton('Limpar',null,'Limpar',null,null,FALSE,FALSE);
$frm->addButton('Ir para cliente','Cliente','btnCliente',null,null,FALSE,TRUE);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
        $frm->clearFields();
        $frm->setFocusField('NMCLIENTE');
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
                    $frm->setFocusField('NMCLIENTE');
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
                ,'IDPEDIDO'=>$frm->get('IDPEDIDOBUSCA')
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] ) && $_REQUEST['ajax'] ){
	$maxRows = LINHAS_POR_PAGINA;
	$whereGrid = getWhereGridParametersPedido( $frm );
	$whereGrid['STATIVO'] = STATUS_ATIVO;
	$page = PostHelper::get('page');
	$dados = Pedido::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
	$realTotalRowsSqlPaginator = Pedido::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',IDCLIENTE|IDCLIENTE'
					.',NMCLIENTE|NMCLIENTE'
                    .',DTPEDIDO|DTPEDIDO' 
					;
	$tituloGride = 'Lista de pedidos -'.' Quantidade: '.$realTotalRowsSqlPaginator;
	$gride = new TGrid( 'gd'                // id do gride
					   ,$tituloGride // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields( $mixUpdateFields );
	$gride->setUrl( 'pedido.php' );
	$gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Pedido',null,'center');
	$gride->addColumnCompact('NMCLIENTE','Cliente',null,null,80);
	$gride->addColumn('NRCPFCNPJ','CPF/CNPJ',null,'center');
	$gride->addColumn('DTPEDIDO','Data',null,'center');

	$gride->addButton('Adicionar itens no pedido','gd_itens','btnItens',null,null,'images/gtk_add_17px.png');
	$gride->addButton('Alterar','gd_alterar','btnAlterar',null,null,'alterar.gif');
	$gride->addButton('Excluir','gd_excluir','btnExcluir',null,'Confirma a exclusão do pedido?','lixeira.gif');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->setFocusField('NMCLIENTE');
$frm->show();

?>
<script>
function init() {
	var Parameters = {"BUSCAR":""
					,"IDPEDIDO":""
					,"IDCLIENTE":""
					,"NMCLIENTE":""
					,"DTPEDIDO":"" 
                    ,"IDPEDIDOBUSCA":""

					};
	fwGetGrid('pedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>
