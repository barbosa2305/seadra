<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDUNIDADEFEDERATIVA';
$frm = new TForm('vw_municipios',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addTextField('DSSIGLA', 'DSSIGLA',2,TRUE,2);
$frm->addNumberField('CDMUNICIPIO', 'CDMUNICIPIO',10,FALSE,0);
$frm->addMemoField('NMMUNICIPIO', 'NMMUNICIPIO',200,TRUE,80,3);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
}


function getWhereGridParameters(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDUNIDADEFEDERATIVA'=>$frm->get('IDUNIDADEFEDERATIVA')
				,'DSSIGLA'=>$frm->get('DSSIGLA')
				,'CDMUNICIPIO'=>$frm->get('CDMUNICIPIO')
				,'NMMUNICIPIO'=>$frm->get('NMMUNICIPIO')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Vw_municipios::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Vw_municipios::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',DSSIGLA|DSSIGLA'
					.',CDMUNICIPIO|CDMUNICIPIO'
					.',NMMUNICIPIO|NMMUNICIPIO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Gride with SQL Pagination' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'vw_municipios.php' );

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('DSSIGLA','DSSIGLA');
	$gride->addColumn('CDMUNICIPIO','CDMUNICIPIO');
	$gride->addColumn('NMMUNICIPIO','NMMUNICIPIO');

	$gride->enableDefaultButtons(false);

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
					,"IDUNIDADEFEDERATIVA":""
					,"DSSIGLA":""
					,"CDMUNICIPIO":""
					,"NMMUNICIPIO":""
					};
	fwGetGrid('vw_municipios.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>