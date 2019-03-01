<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDITEMPEDIDO';
$frm = new TForm( 'Itens do pedido',580,850 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey ); // coluna chave da tabela

$g = $frm->addGroupField('gpx1');
	$g->setColumns('65,80,45,80,62');
	$frm->addTextField('IDPEDIDO','Pedido:',12,TRUE)->setEnabled( FALSE );
	$frm->addTextField('NMCLIENTE','Cliente:',93,FALSE,93,null,FALSE)->setEnabled( FALSE );
	$frm->addTextField('DTPEDIDO','Data:',12,FALSE,12,null,TRUE)->setEnabled( FALSE );
	// Inicio campo AutoComplete
	$frm->addTextField('IDPRODUTO','Produto:',12,TRUE,12,null,TRUE);  //campo obrigatorio para funcionar o autocomplete
    $frm->addTextField('NMPRODUTO',null,70,FALSE,70,null,FALSE); //campo obrigatorio para funcionar o autocomplete
	$frm->addNumberField('VLPRECOVENDA', 'Preço (R$):',8,FALSE,2,FALSE)->setEnabled( FALSE );
	$frm->setAutoComplete('NMPRODUTO','produto','NMPRODUTO','IDPRODUTO|IDPRODUTO,NMPRODUTO|NMPRODUTO,VLPRECOVENDA|VLPRECOVENDA'
						,TRUE,null,null,3,500,50,null,null,null,null,TRUE,null,null,TRUE);
	// Fim campo AutoComplete
	$frm->addNumberField('QTITEMPEDIDO','Quantidade:',12,TRUE,0);
	$frm->addHtmlField('html1', '<br>* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');
$g->closeGroup();

$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
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
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ){
	if ( !empty($frm->get('IDPEDIDO')) ) {
		$maxRows = ROWS_PER_PAGE;
		$whereGrid = getWhereGridParameters( $frm );
		$whereGrid['IDPEDIDO'] = $frm->get('IDPEDIDO');
		$whereGrid['STATIVO'] = STATUS_ATIVO;
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
	} else {
		$gride = new TGrid( 'gd','Lista de itens do pedido');
	}

	$gride->addColumn('NRITEM','Item');
    $gride->addColumnCompact('NMPRODUTO','Produto',null,null,60);
    $gride->addColumn('DSUNIDADEMEDIDA','Unidade');
	$gride->addColumn('QTITEMPEDIDO','Quantidade');
	$gride->addColumn('VLPRECOVENDA','Valor unitário (R$)');
	$gride->addColumn('VLTOTALITEM','Valor total (R$)');

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
</script>
