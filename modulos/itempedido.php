<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDITEMPEDIDO';
$frm = new TForm( 'Itens do pedido',605,1100 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey ); // coluna chave da tabela

$frm->setColumns('47,45,42,40,30,45,55,50,59,40,97');
$frm->addGroupField('gpPedido','Pedido');
	$frm->addTextField('IDPEDIDO','Número:',10,TRUE,6)->setEnabled( FALSE );
	$frm->addTextField('NMCLIENTE','Cliente:',255,FALSE,47,null,FALSE)->setEnabled( FALSE );
    $frm->addTextField('DTPEDIDO','Data:',10,FALSE,8,null,FALSE)->setEnabled( FALSE );
	$frm->addNumberField('VLPEDIDO', 'Total (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->addNumberField('VLTOTALDESCONTO', 'Desc. (R$):',6,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->addNumberField('VLTOTAL', 'Total c/ desc. (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	getValoresPedido( $frm );
$frm->closeGroup();

$frm->setColumns('47,50,65,50,80,50,26,50,78');
$frm->addGroupField('gpItens','Itens');
	$frm->addTextField('IDPRODUTO','Produto:',10,TRUE,6,null,TRUE);  // campo obrigatorio para funcionar o autocomplete
    $frm->addTextField('NMPRODUTO',null,255,FALSE,75,null,FALSE); // campo obrigatorio para funcionar o autocomplete
	$frm->addNumberField('VLPRECOVENDA', 'Valor unit. (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->addNumberField('QTITEMPEDIDO','Qtd:',6,TRUE,0,FALSE);
	$frm->addNumberField('VLDESCONTO', 'Desconto (R$):',8,FALSE,2,FALSE);
	$frm->setAutoComplete('NMPRODUTO','vw_produto_ativo','NMPRODUTO','IDPRODUTO|IDPRODUTO,NMPRODUTO|NMPRODUTO,VLPRECOVENDA|VLPRECOVENDA'
						,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
$frm->closeGroup();
$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
if ( !empty($frm->get('IDPEDIDO')) ) {
    $frm->addButton('Imprimir',null,'btnImprimir','exibir_pdf()',null,FALSE,FALSE)->setEnabled( TRUE );
} else {
    $frm->addButton('Imprimir',null,'btnImprimir','exibir_pdf()',null,FALSE,FALSE)->setEnabled( FALSE );
}
$frm->addButton('Limpar',null,'Limpar',null,null,FALSE,FALSE);
$frm->addButton('Voltar para pedido','Voltar','btnVoltar',null,null,FALSE,FALSE);
$frm->addButton('Ir para produto','Produto','btnProduto',null,null,FALSE,TRUE);

function getCamposNaoLimpar() {
    return array( 'IDPEDIDO','NMCLIENTE','DTPEDIDO','VLPEDIDO','VLTOTALDESCONTO','VLTOTAL' );
}

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$naoLimpar = getCamposNaoLimpar();
		$frm->clearFields( null,$naoLimpar );
	break;
	//--------------------------------------------------------------------------------
	case 'Salvar':
		try{
			if ( $frm->validate() ){
				$vo = new ItempedidoVO();
				$frm->setVo( $vo );
				$vo->setIdusuario( Acesso::getUserId() ); // IdUsuario que será gravado em Pedido
				$resultado = Itempedido::save( $vo );
				if ( $resultado == 1){
					getValoresPedido( $frm );
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					$naoLimpar = getCamposNaoLimpar();
            		$frm->clearFields( null,$naoLimpar );
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
			$resultado = Itempedido::delete( $id );
			if ( $resultado == 1 ){
				getValoresPedido( $frm );
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$naoLimpar = getCamposNaoLimpar();
				$frm->clearFields( null,$naoLimpar );
			} else {
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
	case 'Voltar':
		$frm->setFieldValue( 'BUSCAR',null );
		$frm->setFieldValue( 'IDPEDIDO',null );
		$frm->setFieldValue( 'NMCLIENTE',null );
		$frm->setFieldValue( 'DTPEDIDO',null );
		$frm->redirect( 'pedido.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
	case 'Produto':
		$frm->setFieldValue( 'BUSCAR',null );
		$frm->setFieldValue( 'IDPRODUTO',null );
        $frm->setFieldValue( 'NMPRODUTO',null );
        $frm->setFieldValue( 'VLPRECOVENDA',null );
		$frm->redirect( 'produto.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
}

function getValoresPedido(&$frm){
	$idPedido = $frm->get('IDPEDIDO');
    if ( !empty($idPedido) ){
        $where = array( 'IDPEDIDO'=>$idPedido );
		$dadosPedido = Itempedido::selectAll( null,$where );
		$frm->setFieldValue( 'VLPEDIDO',$dadosPedido['VLPEDIDO'][0] );
		$frm->setFieldValue( 'VLTOTALDESCONTO',$dadosPedido['VLTOTALDESCONTO'][0] );
		$frm->setFieldValue( 'VLTOTAL',$dadosPedido['VLTOTAL'][0] );
    } else {
		$frm->setFieldValue( 'VLPEDIDO',null );
		$frm->setFieldValue( 'VLTOTALDESCONTO',null );
		$frm->setFieldValue( 'VLTOTAL',null );
	}
}

function getWhereGridParameters(&$frm){
	$retorno = null;
	if ( $frm->get('BUSCAR') == 1 ){
		$retorno = array(
						'IDITEMPEDIDO'=>$frm->get('IDITEMPEDIDO')
						,'IDPEDIDO'=>$frm->get('IDPEDIDO')
						,'IDPRODUTO'=>$frm->get('IDPRODUTO') 
						,'QTITEMPEDIDO'=>$frm->get('QTITEMPEDIDO')
						//,'VLDESCONTO'=>$frm->get('VLDESCONTO')
						);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ){
	if ( !empty($frm->get('IDPEDIDO')) ) {
		$maxRows = LINHAS_POR_PAGINA;
		$whereGrid = getWhereGridParameters( $frm );
		$whereGrid['IDPEDIDO'] = $frm->get('IDPEDIDO');
		$page = PostHelper::get('page');
		$orderBy = 'NMPRODUTO';
		$dados = Itempedido::selectAllPagination( $orderBy,$whereGrid,$page,$maxRows );
		$realTotalRowsSqlPaginator = Itempedido::selectCount( $whereGrid );
		$mixUpdateFields = $primaryKey.'|'.$primaryKey
						.',IDPEDIDO|IDPEDIDO'
						.',IDPRODUTO|IDPRODUTO'
						.',NMPRODUTO|NMPRODUTO'
						.',VLPRECOVENDA|VLPRECOVENDA'
						.',QTITEMPEDIDO|QTITEMPEDIDO' 
						.',VLDESCONTO|VLDESCONTO'
						;
		$tituloGride = 'Lista de itens do pedido -'.' Quantidade: '.$realTotalRowsSqlPaginator;
		$gride = new TGrid( 'gd'                        // id do gride
						,$tituloGride // titulo do gride
						);
		$gride->addKeyField( $primaryKey ); // chave primaria
		$gride->setData( $dados ); // array de dados
		$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
		$gride->setMaxRows( $maxRows );
		$gride->setUpdateFields( $mixUpdateFields );
		$gride->setUrl( 'itempedido.php' );
		$gride->setZebrarColors( '#ffffff','#ffffff' );
	} else {
		$tituloGride = 'Lista de itens do pedido - Quantidade: 0';
		$gride = new TGrid( 'gd',$tituloGride);
	}

	//$gride->addColumn('NRITEM','Item',null,'center');
	$gride->addColumn('IDPRODUTOFORMATADO','Código',null,'center');
    $gride->addColumnCompact('NMPRODUTO','Produto',null,null,110);
    $gride->addColumn('DSUNIDADEMEDIDA','Unid.',null,'center');
	$gride->addColumn('VLPRECOVENDA','Valor unit. (R$)',null,'right');
	$gride->addColumn('QTITEMPEDIDO','Qtd',null,'center');
	$gride->addColumn('VLTOTALITEM','Total (R$)',null,'right');
	$gride->addColumn('VLDESCONTO','Desc. (R$)',null,'right');
	$gride->addColumn('VLTOTALITEMCOMDESCONTO','Total c/ desc. (R$)',null,'right');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->show();

?>
<script>
function init() {
	var Parameters = {
					"BUSCAR":""
					,"IDITEMPEDIDO":""
					,"IDPEDIDO":""
					,"IDPRODUTO":""
					,"NMPRODUTO":""
					,"VLPRECOVENDA":""
					,"QTITEMPEDIDO":""
					,"VLDESCONTO":""
					};
	fwGetGrid('itempedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
function exibir_pdf() {
    var jsonParams = {
                        "modulo" : "relatorios/rel_pedido.php"
                        ,"titulo" : "Pedido Nr. " + jQuery("#IDPEDIDO").val() 
                        ,"IDPEDIDO" : jQuery("#IDPEDIDO").val()
                    };
    fwShowPdf(jsonParams);
}
</script>
