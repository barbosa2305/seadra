<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDHORARIOVERAO';
$frm = new TForm('Horário de verão',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);

$frm->addHiddenField( 'BUSCAR' ); // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->setColumns('38,40,28,40');
$frm->addGroupField('gpx1');
	$frm->addTextField('DTINICIO','Início:',19,TRUE,20,null,TRUE);
	$frm->addTextField('DTFIM','Fim:',19,TRUE,20,null,FALSE);
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
				$frm->setVo( $vo );
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
		$retorno = array(
				'IDHORARIOVERAO'=>$frm->get('IDHORARIOVERAO')
				,'DTINICIO'=>$frm->get('DTINICIO')
				,'DTFIM'=>$frm->get('DTFIM')
		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters($frm);
	$page = PostHelper::get('page');
	$dados = Horarioverao::selectAllPagination( $primaryKey, $whereGrid, $page,  $maxRows);
	$realTotalRowsSqlPaginator = Horarioverao::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',DTINICIO|DTINICIO'
					.',DTFIM|DTFIM'
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

	$gride->addColumn($primaryKey,'id');
	$gride->addColumn('DTINICIOFORMATADA','Início');
	$gride->addColumn('DTFIMFORMATADA','Fim');

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