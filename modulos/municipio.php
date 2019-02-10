<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDMUNICIPIO';
$frm = new TForm('municipio',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addNumberField('CDMUNICIPIO', 'CDMUNICIPIO',10,FALSE,0);
$frm->addMemoField('NMMUNICIPIO', 'NMMUNICIPIO',200,TRUE,80,3);
$listUnidadefederativa = Unidadefederativa::selectAll();
$frm->addSelectField('IDUNIDADEFEDERATIVA', 'IDUNIDADEFEDERATIVA',TRUE,$listUnidadefederativa,null,null,null,null,null,null,' ',null);
$frm->addTextField('STATIVO', 'STATIVO',1,TRUE,1);

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
				$vo = new MunicipioVO();
				$frm->setVo( $vo );
				$resultado = Municipio::save( $vo );
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
			$resultado = Municipio::delete( $id );;
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
				'IDMUNICIPIO'=>$frm->get('IDMUNICIPIO')
				,'CDMUNICIPIO'=>$frm->get('CDMUNICIPIO')
				,'NMMUNICIPIO'=>$frm->get('NMMUNICIPIO')
				,'IDUNIDADEFEDERATIVA'=>$frm->get('IDUNIDADEFEDERATIVA')
				,'STATIVO'=>$frm->get('STATIVO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Municipio::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Municipio::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',CDMUNICIPIO|CDMUNICIPIO'
					.',NMMUNICIPIO|NMMUNICIPIO'
					.',IDUNIDADEFEDERATIVA|IDUNIDADEFEDERATIVA'
					.',STATIVO|STATIVO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Gride with SQL Pagination' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'municipio.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('CDMUNICIPIO','CDMUNICIPIO');
	$gride->addColumn('NMMUNICIPIO','NMMUNICIPIO');
	$gride->addColumn('IDUNIDADEFEDERATIVA','IDUNIDADEFEDERATIVA');
	$gride->addColumn('STATIVO','STATIVO');


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
					,"IDMUNICIPIO":""
					,"CDMUNICIPIO":""
					,"NMMUNICIPIO":""
					,"IDUNIDADEFEDERATIVA":""
					,"STATIVO":""
					};
	fwGetGrid('municipio.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>