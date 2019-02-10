<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDCLIENTE';
$frm = new TForm('cliente',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addMemoField('NMCLIENTE', 'NMCLIENTE',255,TRUE,80,3);
$frm->addTextField('NRCPFCNPJ', 'NRCPFCNPJ',30,TRUE,30);
$frm->addTextField('DSEMAIL', 'DSEMAIL',100,FALSE,100);
$frm->addTextField('NRTELEFONE', 'NRTELEFONE',20,FALSE,20);
$frm->addTextField('NRCELULAR', 'NRCELULAR',20,FALSE,20);
$listEndereco = Endereco::selectAll();
$frm->addSelectField('IDENDERECO', 'IDENDERECO',FALSE,$listEndereco,null,null,null,null,null,null,' ',null);
$frm->addMemoField('DSCOMPLEMENTOENDERECO', 'DSCOMPLEMENTOENDERECO',255,FALSE,80,3);
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
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new ClienteVO();
				$frm->setVo( $vo );
				$resultado = Cliente::save( $vo );
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
			$resultado = Cliente::delete( $id );;
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
				'IDCLIENTE'=>$frm->get('IDCLIENTE')
				,'NMCLIENTE'=>$frm->get('NMCLIENTE')
				,'NRCPFCNPJ'=>$frm->get('NRCPFCNPJ')
				,'DSEMAIL'=>$frm->get('DSEMAIL')
				,'NRTELEFONE'=>$frm->get('NRTELEFONE')
				,'NRCELULAR'=>$frm->get('NRCELULAR')
				,'IDENDERECO'=>$frm->get('IDENDERECO')
				,'DSCOMPLEMENTOENDERECO'=>$frm->get('DSCOMPLEMENTOENDERECO')
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
	$dados = Cliente::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Cliente::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',NMCLIENTE|NMCLIENTE'
					.',NRCPFCNPJ|NRCPFCNPJ'
					.',DSEMAIL|DSEMAIL'
					.',NRTELEFONE|NRTELEFONE'
					.',NRCELULAR|NRCELULAR'
					.',IDENDERECO|IDENDERECO'
					.',DSCOMPLEMENTOENDERECO|DSCOMPLEMENTOENDERECO'
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
	$gride->setUrl( 'cliente.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('NMCLIENTE','NMCLIENTE');
	$gride->addColumn('NRCPFCNPJ','NRCPFCNPJ');
	$gride->addColumn('DSEMAIL','DSEMAIL');
	$gride->addColumn('NRTELEFONE','NRTELEFONE');
	$gride->addColumn('NRCELULAR','NRCELULAR');
	$gride->addColumn('IDENDERECO','IDENDERECO');
	$gride->addColumn('DSCOMPLEMENTOENDERECO','DSCOMPLEMENTOENDERECO');
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
					,"IDCLIENTE":""
					,"NMCLIENTE":""
					,"NRCPFCNPJ":""
					,"DSEMAIL":""
					,"NRTELEFONE":""
					,"NRCELULAR":""
					,"IDENDERECO":""
					,"DSCOMPLEMENTOENDERECO":""
					,"STATIVO":""
					,"IDUSUARIOCRIACAO":""
					,"DTCRIACAO":""
					,"IDUSUARIOMODIFICACAO":""
					,"DTMODIFICACAO":""
					};
	fwGetGrid('cliente.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>