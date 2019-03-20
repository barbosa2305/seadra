<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDCONFIGURACAO';
$frm = new TForm('Configuração',500,860);
$frm->setFlat(TRUE);
$frm->setMaximize(TRUE);
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' ); // Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->setColumns('60');
$frm->addGroupField('gpEmitente','Emitente');
	$frm->addTextField('DSEMITENTE', 'Nome:',200,FALSE,130);
	$frm->addMemoField('DSENDERECOEMITENTE', 'Endereço:',400,FALSE,115,3);
	$frm->addTextField('DSTELEFONEEMITENTE', 'Telefone:',20,FALSE,15);
$frm->closeGroup();
$frm->addHtmlField('html1', '* Essas configurações serão utilizadas no relatório: Pedido de Venda.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	//--------------------------------------------------------------------------------
	case 'Salvar':
		try {
			if ( $frm->validate() ){
				$vo = new ConfiguracaoVO();
				$frm->setVo( $vo );
				$resultado = Configuracao::save( $vo );
				if ( $resultado == 1 ){
					$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
					$frm->clearFields();
				} else {
					$frm->setMessage( Mensagem::OPERACAO_FALHOU );
				}
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
}

function getWhereGridParameters( &$frm ){
	$retorno = null;
	if ($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDCONFIGURACAO'=>$frm->get('IDCONFIGURACAO')
				,'DSEMITENTE'=>$frm->get('DSEMITENTE')
				,'DSENDERECOEMITENTE'=>$frm->get('DSENDERECOEMITENTE')
				,'DSTELEFONEEMITENTE'=>$frm->get('DSTELEFONEEMITENTE')
		);
	}
	return $retorno;
}

if ( isset( $_REQUEST['ajax'] ) && $_REQUEST['ajax'] ){
	$maxRows = ROWS_PER_PAGE;
	$whereGrid = getWhereGridParameters( $frm );
	$page = PostHelper::get('page');
	$dados = Configuracao::selectAllPagination( $primaryKey,$whereGrid,$page,$maxRows );
	$realTotalRowsSqlPaginator = Configuracao::selectCount( $whereGrid );
	$mixUpdateFields = $primaryKey.'|'.$primaryKey
					.',DSEMITENTE|DSEMITENTE'
					.',DSENDERECOEMITENTE|DSENDERECOEMITENTE'
					.',DSTELEFONEEMITENTE|DSTELEFONEEMITENTE'
					;
	$tituloGride = 'Configurações -'.' Quantidade: '.$realTotalRowsSqlPaginator;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,$tituloGride // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields( $mixUpdateFields );
	$gride->setUrl( 'configuracao.php' );
	$gride->setCreateDefaultDeleteButton( FALSE );
	$gride->setExportExcel( FALSE );
	$gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Código',null,'center');
	$gride->addColumn('DSEMITENTE','Emitente');
	$gride->addColumnCompact('DSENDERECOEMITENTE','Endereço emitente',null,null,108);
	$gride->addColumn('DSTELEFONEEMITENTE','Telefone emitente');

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
					,"IDCONFIGURACAO":""
					,"DSEMITENTE":""
					,"DSENDERECOEMITENTE":""
					,"DSTELEFONEEMITENTE":""
					};
	fwGetGrid('configuracao.php','gride',Parameters,true);
}
function buscar() {
	jQuery("#BUSCAR").val(1);
	init();
}
</script>