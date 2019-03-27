<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDHORARIOVERAO';
$frm = new TForm('Horário de verão',500,640);
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' ); // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->setColumns('35,105,35,40');
$frm->addGroupField('gpInicio','Início',null,300,TRUE);
	$frm->addDateField('DTINICIO','Data:',TRUE,TRUE);
	$frm->addTimeField('HRINICIO','Hora:',TRUE,'00:00:00','23:59:59','HMS',FALSE);
$frm->closeGroup();

$frm->setColumns('35,105,35,40');
$frm->addGroupField('gpFim','Fim',null,300,FALSE);
	$frm->addDateField('DTFIM','Data:',TRUE,FALSE);
	$frm->addTimeField('HRFIM','Hora:',TRUE,'00:00:00','23:59:59','HMS',FALSE);
$frm->closeGroup();
$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

//$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, TRUE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	case 'Salvar':
		try {
			if ( $frm->validate() ){
				$vo = new HorarioveraoVO();
				$vo->setIdhorarioverao( $frm->get($primaryKey) );
				$vo->setDtinicio( $frm->get('DTINICIO').' '.$frm->get('HRINICIO') );
				$vo->setDtfim( $frm->get('DTFIM').' '.$frm->get('HRFIM') );
				$resultado = Horarioverao::save( $vo );
				if ( $resultado == 1 ){
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					$frm->clearFields();
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
			$resultado = Horarioverao::delete( $id );
			if ( $resultado == 1 ){
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->clearFields();
				$frm->setMessage($resultado);
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
}

function getWhereGridParameters(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		/*
		$dtInicio = $frm->get('DTINICIO').' '.$frm->get('HRINICIO');
		$retorno = array(
				'IDHORARIOVERAO'=>$frm->get('IDHORARIOVERAO')
				,'DTINICIO'=>$dtInicio
				,''
				,'DTFIM'=>$frm->get('DTFIM').' '.$frm->get('HRFIM')
		);
		*/
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Horarioverao::selectAllPagination( 'DTINICIO DESC', $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Horarioverao::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',DTINICIOFORMATADA|DTINICIO'
					.',HRINICIOFORMATADA|HRINICIO'
					.',DTFIMFORMATADA|DTFIM'
					.',HRFIMFORMATADA|HRFIM'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Lista de horários de verão'.' - Quantidade: '.$realTotalRowsSqlPaginator // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'horarioverao.php' );
	$gride->setExportExcel( FALSE );
	$gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Código',50,'center');
	$gride->addColumn('DTINICIOFORMATADA','Data início',null,'center');
	$gride->addColumn('HRINICIOFORMATADA','Hora início',null,'center');
	$gride->addColumn('DTFIMFORMATADA','Data fim',null,'center');
	$gride->addColumn('HRFIMFORMATADA','Hora fim',null,'center');

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
					,"IDHORARIOVERAO":""
					,"DTINICIO":""
					,"DTFIM":""
					};
	fwGetGrid('horarioverao.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>