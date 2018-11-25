<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDENDERECO';
$frm = new TForm('endereco',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addTextField('DSCEP', 'DSCEP',8,TRUE,8);
$frm->addMemoField('DSLOGRADOURO', 'DSLOGRADOURO',255,TRUE,80,3);
$frm->addTextField('DSBAIRRO', 'DSBAIRRO',100,TRUE,100);
$frm->addTextField('DSLOCALIDADE', 'DSLOCALIDADE',100,TRUE,100);
$listMunicipio = Municipio::selectAll();
$frm->addSelectField('IDMUNICIPIO', 'IDMUNICIPIO',TRUE,$listMunicipio,null,null,null,null,null,null,' ',null);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	case 'Salvar':
		try{
			if ( $frm->validate() ) {
				$vo = new EnderecoVO();
				$frm->setVo( $vo );
				$resultado = Endereco::save( $vo );
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
			$resultado = Endereco::delete( $id );;
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
				'IDENDERECO'=>$frm->get('IDENDERECO')
				,'DSCEP'=>$frm->get('DSCEP')
				,'DSLOGRADOURO'=>$frm->get('DSLOGRADOURO')
				,'DSBAIRRO'=>$frm->get('DSBAIRRO')
				,'DSLOCALIDADE'=>$frm->get('DSLOCALIDADE')
				,'IDMUNICIPIO'=>$frm->get('IDMUNICIPIO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Endereco::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Endereco::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',DSCEP|DSCEP'
					.',DSLOGRADOURO|DSLOGRADOURO'
					.',DSBAIRRO|DSBAIRRO'
					.',DSLOCALIDADE|DSLOCALIDADE'
					.',IDMUNICIPIO|IDMUNICIPIO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Gride with SQL Pagination' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'endereco.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('DSCEP','DSCEP');
	$gride->addColumn('DSLOGRADOURO','DSLOGRADOURO');
	$gride->addColumn('DSBAIRRO','DSBAIRRO');
	$gride->addColumn('DSLOCALIDADE','DSLOCALIDADE');
	$gride->addColumn('IDMUNICIPIO','IDMUNICIPIO');

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
					,"IDENDERECO":""
					,"DSCEP":""
					,"DSLOGRADOURO":""
					,"DSBAIRRO":""
					,"DSLOCALIDADE":""
					,"IDMUNICIPIO":""
					};
	fwGetGrid('endereco.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>