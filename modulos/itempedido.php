<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDITEMPEDIDO';
$frm = new TForm('itempedido',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$list = Itempedido::selectAll();
$frm->addSelectField('IDPEDIDO', 'IDPEDIDO',TRUE,$list,null,null,null,null,null,null,' ',null);
$listPedido = Pedido::selectAll();
$frm->addSelectField('IDPEDIDO', 'IDPEDIDO',TRUE,$listPedido,null,null,null,null,null,null,' ',null);
$list = Itempedido::selectAll();
$frm->addSelectField('IDPRODUTO', 'IDPRODUTO',TRUE,$list,null,null,null,null,null,null,' ',null);
$listProduto = Produto::selectAll();
$frm->addSelectField('IDPRODUTO', 'IDPRODUTO',TRUE,$listProduto,null,null,null,null,null,null,' ',null);
$frm->addNumberField('QTITEMPEDIDO', 'QTITEMPEDIDO',10,TRUE,0);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new ItempedidoVO();
				$frm->setVo( $vo );
				$resultado = Itempedido::save( $vo );
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
			$resultado = Itempedido::delete( $id );;
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
				'IDITEMPEDIDO'=>$frm->get('IDITEMPEDIDO')
				,'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDPEDIDO'=>$frm->get('IDPEDIDO')
				,'IDPRODUTO'=>$frm->get('IDPRODUTO')
				,'IDPRODUTO'=>$frm->get('IDPRODUTO')
				,'QTITEMPEDIDO'=>$frm->get('QTITEMPEDIDO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Itempedido::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Itempedido::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',IDPEDIDO|IDPEDIDO'
					.',IDPEDIDO|IDPEDIDO'
					.',IDPRODUTO|IDPRODUTO'
					.',IDPRODUTO|IDPRODUTO'
					.',QTITEMPEDIDO|QTITEMPEDIDO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Gride with SQL Pagination' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'itempedido.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('IDPEDIDO','IDPEDIDO');
	$gride->addColumn('IDPEDIDO','IDPEDIDO');
	$gride->addColumn('IDPRODUTO','IDPRODUTO');
	$gride->addColumn('IDPRODUTO','IDPRODUTO');
	$gride->addColumn('QTITEMPEDIDO','QTITEMPEDIDO');


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
					,"IDPEDIDO":""
					,"IDPRODUTO":""
					,"IDPRODUTO":""
					,"QTITEMPEDIDO":""
					};
	fwGetGrid('itempedido.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>