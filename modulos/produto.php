<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPRODUTO';
$frm = new TForm( 'Produto',640,830 );
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' );  // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->setColumns('42');
$frm->addGroupField('gpx1','Busca por');
    $frm->addNumberField('IDPRODUTOBUSCA','Código:',6,FALSE,0,TRUE)->setExampleText('Utilizar este campo apenas para busca.');
$frm->closeGroup();

$frm->setColumns('58,50,90,80,95,50,40');
$frm->addGroupField('gpx2','Dados do produto');
    $frm->addTextField('NMPRODUTO', 'Descrição:',255,TRUE,119,null,TRUE);
    $unidadeMedida = array('MT'=>'Metro','M2'=>'Metro quadrado','M3'=>'Metro cubico','UN'=>'Unidade');
    $frm->addSelectField('DSUNIDADEMEDIDA', 'Unidade:',TRUE,$unidadeMedida,TRUE,null,null,null,null,null,null,null);
	$frm->addNumberField('VLPRECOCUSTO', 'Preço custo (R$):',10,TRUE,2,FALSE);
	$frm->addNumberField('VLPRECOVENDA', 'Preço venda (R$):',10,TRUE,2,FALSE);
	$ativo = array('S' => 'Sim', 'N' => 'Não');
	$frm->addSelectField('STATIVO', 'Ativo ?',FALSE,$ativo,FALSE,null,null,null,null,null,null,null);
$frm->closeGroup();
$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar',null,'btnBuscar','buscar()',null,TRUE,FALSE);
$frm->addButton('Salvar',null,'Salvar',null,null,FALSE,FALSE);
$frm->addButton('Limpar',null,'Limpar',null,null,FALSE,FALSE);
$frm->addButton('Ir para pedido','Pedido',null,null,null,FALSE,TRUE);

$acao = isset($acao) ? $acao : null;
switch( $acao ){
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'Salvar':
		try{
			if ( $frm->validate() ){
				$vo = new ProdutoVO();
				$frm->setVo( $vo );
				$vo->setIdusuario( Acesso::getUserId() );
				$resultado = Produto::save( $vo );
				if ( $resultado == 1 ) {
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
                    $frm->clearFields();
                    $frm->setFocusField('NMPRODUTO');
				} else {
					$frm->setMessage( Mensagem::OPERACAO_FALHOU );
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
			$resultado = Produto::delete( $id );
			if ( $resultado == 1 ){
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->clearFields();
				$frm->setMessage( Mensagem::OPERACAO_FALHOU );
			}
		}
		catch ( DomainException $e ) {
			$frm->setMessage( $e->getMessage() );
		}
		catch ( Exception $e ) {
			MessageHelper::logRecord( $e );
			$frm->setMessage( $e->getMessage() );
		}
	break;
	//--------------------------------------------------------------------------------
	case 'Pedido':
		$frm->setFieldValue( 'BUSCAR',null );
		$frm->setFieldValue( 'IDPRODUTO',null );
		$frm->setFieldValue( 'NMPRODUTO',null );
		$frm->redirect( 'pedido.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
}

function getWhereGridParameters(&$frm){
	$retorno = null;
	if ( $frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'NMPRODUTO'=>$frm->get('NMPRODUTO')
				,'VLPRECOCUSTO'=>TrataDados::converteMoeda( $frm->get('VLPRECOCUSTO') )
				,'VLPRECOVENDA'=>TrataDados::converteMoeda( $frm->get('VLPRECOVENDA') )
                ,'STATIVO'=>$frm->get('STATIVO')
                ,'IDPRODUTO'=>$frm->get('IDPRODUTOBUSCA')
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ){
	$maxRows = LINHAS_POR_PAGINA;
	$whereGrid = getWhereGridParameters( $frm );
	$page = PostHelper::get('page');
	$dados = Produto::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
	$realTotalRowsSqlPaginator = Produto::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
                    .',NMPRODUTO|NMPRODUTO'
                    .',DSUNIDADEMEDIDA|DSUNIDADEMEDIDA'
					.',VLPRECOCUSTO|VLPRECOCUSTO'
                    .',VLPRECOVENDA|VLPRECOVENDA'
					.',STATIVO|STATIVO'
					;
	$tituloGride = 'Lista de produtos -'.' Quantidade: '.$realTotalRowsSqlPaginator;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,$tituloGride // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields( $mixUpdateFields );
    $gride->setUrl( 'produto.php' );
    $gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Código',null,'center');
    $gride->addColumnCompact('NMPRODUTO','Descrição',null,null,65);
    $gride->addColumn('DSUNIDADEMEDIDA','Unidade',null,'center');
	$gride->addColumn('VLPRECOCUSTO','Preço custo (R$)',null,'right');
	$gride->addColumn('VLPRECOVENDA','Preço venda (R$)',null,'right');
	$gride->addColumn('DSATIVO','Ativo ?',null,'center');

	$gride->show();
	die();
}

$frm->addHtmlField('gride');
$frm->addJavascript('init()');
$frm->setFocusField('NMPRODUTO');
$frm->show();

?>
<script>
function init() {
	var Parameters = {"BUSCAR":""
					,"IDPRODUTO":""
					,"NMPRODUTO":""
					,"VLPRECOCUSTO":""
					,"VLPRECOVENDA":""
					,"STATIVO":""
                    ,"IDPRODUTOBUSCA":""
					};
	fwGetGrid('produto.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>