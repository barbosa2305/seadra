<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDPRODUTO';
$frm = new TForm('produto',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addMemoField('NMPRODUTO', 'NMPRODUTO',255,TRUE,80,3);
$frm->addNumberField('VLPRECOCUSTO', 'VLPRECOCUSTO',10,FALSE,2);
$frm->addNumberField('VLPRECOVENDA', 'VLPRECOVENDA',10,FALSE,2);
$frm->addTextField('STATIVO', 'STATIVO',1,TRUE,1);
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
				$vo = new ProdutoVO();
				$frm->setVo( $vo );
				$resultado = Produto::save( $vo );
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
			$resultado = Produto::delete( $id );;
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
				'IDPRODUTO'=>$frm->get('IDPRODUTO')
				,'NMPRODUTO'=>$frm->get('NMPRODUTO')
				,'VLPRECOCUSTO'=>$frm->get('VLPRECOCUSTO')
				,'VLPRECOVENDA'=>$frm->get('VLPRECOVENDA')
				,'STATIVO'=>$frm->get('STATIVO')
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
	$dados = Produto::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Produto::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',NMPRODUTO|NMPRODUTO'
					.',VLPRECOCUSTO|VLPRECOCUSTO'
					.',VLPRECOVENDA|VLPRECOVENDA'
					.',STATIVO|STATIVO'
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
	$gride->setUrl( 'produto.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('NMPRODUTO','NMPRODUTO');
	$gride->addColumn('VLPRECOCUSTO','VLPRECOCUSTO');
	$gride->addColumn('VLPRECOVENDA','VLPRECOVENDA');
	$gride->addColumn('STATIVO','STATIVO');
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
					,"IDPRODUTO":""
					,"NMPRODUTO":""
					,"VLPRECOCUSTO":""
					,"VLPRECOVENDA":""
					,"STATIVO":""
					,"IDUSUARIOCRIACAO":""
					,"DTCRIACAO":""
					,"IDUSUARIOMODIFICACAO":""
					,"DTMODIFICACAO":""
					};
	fwGetGrid('produto.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>