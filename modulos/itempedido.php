<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDITEMPEDIDO';
$frm = new TForm( 'Itens do pedido',580,950 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey ); // coluna chave da tabela

//$g = $frm->addGroupField('gpPedido','Pedido');
	//$g->setColumns('60,80,60,70,80,60,80,80,60');  // '95,80,90,80,62'
	//$frm->setColumns('40');
$frm->setColumns('50,50,45,50,35');
$frm->addGroupField('gpPedido','Pedido');
	$frm->addTextField('IDPEDIDO','Número:',10,TRUE,5)->setEnabled( FALSE );
	$frm->addTextField('NMCLIENTE','Cliente:',255,FALSE,100,null,FALSE)->setEnabled( FALSE );
	$frm->addTextField('DTPEDIDO','Data:',10,FALSE,13,null,FALSE)->setEnabled( FALSE );
	//$frm->addNumberField('VLTOTAL', 'Valor (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	//$frm->addNumberField('VLDESCONTO', 'Desconto (R$):',8,FALSE,2,FALSE);
	//$frm->addButton('Salvar desconto',null,'btnSalvarDesconto','salvarDesconto()',null,FALSE,FALSE);
	//$frm->addNumberField('VLPAGO', 'Total (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
//$g->closeGroup();
$frm->closeGroup();
	
//$g = $frm->addGroupField('gpItens','Itens');
//	$g->setColumns('50,40,50,50,65,60,85');
$frm->setColumns('50,50,80,50,70,60,85');
$frm->addGroupField('gpItens','Itens');
//$frm->setColumns('100');
//$frm->setColumns('60,80,60,70');
	$frm->addTextField('IDPRODUTO','Produto:',10,TRUE,5,null,TRUE);  //campo obrigatorio para funcionar o autocomplete
    $frm->addTextField('NMPRODUTO',null,255,FALSE,70,null,FALSE); //campo obrigatorio para funcionar o autocomplete
	$frm->addNumberField('QTITEMPEDIDO','Quantidade:',10,TRUE,0,FALSE);
	$frm->addNumberField('VLPRECOVENDA', 'Valor unit. (R$):',7,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->setAutoComplete('NMPRODUTO','vw_produto_ativo','NMPRODUTO','IDPRODUTO|IDPRODUTO,NMPRODUTO|NMPRODUTO,VLPRECOVENDA|VLPRECOVENDA'
						,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
//$g->closeGroup();
$frm->closeGroup();

$frm->addHtmlField('html1', '<br>', null, null, null, null)->setCss('color', 'red');
$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
$frm->addButton('Imprimir',null,'btnImprimir','openModalPDF('.$frm->getFieldValue('IDPEDIDO').','.$frm->getFieldValue('IDPEDIDO').')',null,FALSE,FALSE);
$frm->addButton('Limpar',null,'Limpar',null,null,FALSE,FALSE);
$frm->addButton('Voltar para pedido','Voltar','btnVoltar',null,null,FALSE,FALSE);
$frm->addButton('Ir para produto','Produto','btnProduto',null,null,FALSE,TRUE);

function getCamposNaoLimpar() {
    return array( 'IDPEDIDO','NMCLIENTE','DTPEDIDO' );
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
	case 'SalvarDesconto':
		try{
			$idPedido = $frm->get( 'IDPEDIDO' );
			$vlDesconto = $frm->get( 'VLDESCONTO' );
			$resultado = Itempedido::saveDesconto( $idPedido,$vlDesconto );
			if ( $resultado == 1){
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$naoLimpar = getCamposNaoLimpar();
				$frm->clearFields( null,$naoLimpar );
			} else {
				$frm->setMessage( Mensagem::OPERACAO_FALHOU );
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

function getWhereGridParameters(&$frm){
	$retorno = null;
	if ( $frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDITEMPEDIDO'=>$frm->get('IDITEMPEDIDO')
				,'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDPRODUTO'=>$frm->get('IDPRODUTO') 
				,'QTITEMPEDIDO'=>$frm->get('QTITEMPEDIDO')
				//,'VLDESCONTO'=>TrataDados::converteMoeda( $frm->get('VLDESCONTO') )
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ){
	if ( !empty($frm->get('IDPEDIDO')) ) {
		$maxRows = ROWS_PER_PAGE;
		$whereGrid = getWhereGridParameters( $frm );
		$whereGrid['IDPEDIDO'] = $frm->get('IDPEDIDO');
		//$whereGrid['STATIVO'] = STATUS_ATIVO;
		$page = PostHelper::get('page');
		$dados = Itempedido::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
		$realTotalRowsSqlPaginator = Itempedido::selectCount( $whereGrid );
		$mixUpdateFields = $primaryKey.'|'.$primaryKey
						.',IDPEDIDO|IDPEDIDO'
						.',IDPRODUTO|IDPRODUTO'
						.',NMPRODUTO|NMPRODUTO'
						.',VLPRECOVENDA|VLPRECOVENDA'
						.',QTITEMPEDIDO|QTITEMPEDIDO' 
						;
		$gride = new TGrid( 'gd'                        // id do gride
						,'Lista de itens do pedido' // titulo do gride
						);
		$gride->addKeyField( $primaryKey ); // chave primaria
		$gride->setData( $dados ); // array de dados
		$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
		$gride->setMaxRows( $maxRows );
		$gride->setUpdateFields( $mixUpdateFields );
		$gride->setUrl( 'itempedido.php' );

		//$frm->setFieldValue('VLTOTAL',$dados['VLTOTAL'][0]);
	} else {
		$gride = new TGrid( 'gd','Lista de itens do pedido');
	}

	$gride->addColumn('NRITEM','Item',null,'center');
    $gride->addColumnCompact('NMPRODUTO','Produto',null,null,60);
    $gride->addColumn('DSUNIDADEMEDIDA','Unidade',null,'center');
	$gride->addColumn('QTITEMPEDIDO','Quantidade',null,'center');
	$gride->addColumn('VLPRECOVENDA','Valor unitário (R$)',null,'right');
	$gride->addColumn('VLTOTALITEM','Total item(R$)',null,'right');
	$gride->addColumn('VLTOTAL','Valor pedido(R$)',null,'right');
	$gride->addColumn('VLDESCONTO','Desconto(R$)',null,'right');
	$gride->addColumn('VLPAGO','Total pedido(R$)',null,'right');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');

$frm->setColumns('60,50');
$frm->addGroupField('gpValores','Desconto');
	$frm->addNumberField('VLTOTAL', 'Valor (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->addNumberField('VLDESCONTO', 'Desconto (R$):',8,FALSE,2,FALSE);
	$frm->addButton('Salvar desconto','SalvarDesconto',null,null,null,FALSE,FALSE);
	$frm->addNumberField('VLPAGO', 'Total (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
$frm->closeGroup();

$frm->addJavascript('init()');
$frm->show();

?>
<script>
function init() {
	var Parameters = {"BUSCAR":""
					,"IDITEMPEDIDO":""
					,"IDPEDIDO":""
					,"IDPRODUTO":""
					,"NMPRODUTO":""
					,"VLPRECOVENDA":""
					,"QTITEMPEDIDO":""
					};
	fwGetGrid('itempedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
function openModalPDF(campo,valor) {
	var dados = fwFV2O(campo,valor); // tranforma os parametros enviados pelo gride em um objeto 
	console.log(dados);
	var jsonParams = {"modulo" : "relatorios/rel_orcamento.php"
		             ,"titulo" : "Pedido Nr. " + dados['IDPEDIDO'] 
		             ,"dados"  :dados
		            };
	fwShowPdf(jsonParams);
}
</script>
