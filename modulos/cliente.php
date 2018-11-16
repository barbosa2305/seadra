<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDCLIENTE';
$frm = new TForm('Cadastro de Clientes',800,950);
$frm->setFlat(true);
$frm->setMaximize(true);


$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela
$frm->addTextField('NMCLIENTE', 'Nome',255,TRUE,120);
$frm->addCpfCnpjField('NRCPFCNPJ', 'CPF/CNPJ',TRUE);
$frm->addFoneField('NRTELEFONE', 'Telefone Fixo');
$frm->addFoneField('NRCELULAR', 'Celular');

//$listEndereco = Endereco::selectAll();
//$frm->addSelectField('IDENDERECO', 'IDENDERECO',TRUE,$listEndereco,null,null,null,null,null,null,' ',null);
if (isset($_POST['DSCEP'])) {
	header("Content-Type:text/xml");
	//echo file_get_contents('http://buscarcep.com.br/?cep='.$_POST['num_cep'].'&formato=xml&chave=Chave_Gratuita_BuscarCep&identificador=CLIENTE1');
	echo file_get_contents('https://viacep.com.br/ws/'.$_POST['num_cep'].'/xml/');
    exit;
}

$fldCep = $frm->addCepField('DSCEP', 'CEP', true, null, null, 'DSLOGRADOURO', 'DSBAIRRO', 'DSLOCALIDADE', 'IDUNIDADEFEDERATIVA', null, null, null, null, null, null, 'pesquisarCepCallback', 'pesquisarCepBeforeSend');
$frm->addTextField('DSLOGRADOURO', 'Endereço', 60);
$frm->addTextField('DSCOMPLEMENTOENDERECO', 'Complemento', 100);
$frm->addTextField('DSBAIRRO', 'Bairro', 60);
$frm->addTextField('DSLOCALIDADE', 'Cidade', 60);
$frm->addHiddenField('IDENDERECO', 'Código Endereço');  
$frm->addSelectField('IDUNIDADEFEDERATIVA', 'UF', 2);


//$frm->addTextField('STATIVO', 'STATIVO',1,TRUE,1);
//$listUsuario = Usuario::selectAll();
//$frm->addSelectField('IDUSUARIOCRIACAO', 'IDUSUARIOCRIACAO',TRUE,$listUsuario,null,null,null,null,null,null,' ',null);
//$frm->addDateField('DTCRIACAO', 'DTCRIACAO',TRUE);
//$listUsuario = Usuario::selectAll();
//$frm->addSelectField('IDUSUARIOMODIFICACAO', 'IDUSUARIOMODIFICACAO',FALSE,$listUsuario,null,null,null,null,null,null,' ',null);
//$frm->addDateField('DTMODIFICACAO', 'DTMODIFICACAO',FALSE);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, true, false);
$frm->addButton('Salvar', null, 'Salvar', null, null, false, false);
$frm->addButton('Limpar', null, 'Limpar', null, null, false, false);


$acao = isset($acao) ? $acao : null;
switch( $acao ) {
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
	case 'Limpar':
		$frm->clearFields();
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
				,'IDENDERECO'=>$frm->get('IDENDERECO')
				,'DSCOMPLEMENTOENDERECO'=>$frm->get('DSCOMPLEMENTOENDERECO')
				,'NRTELEFONE'=>$frm->get('NRTELEFONE')
				,'NRCELULAR'=>$frm->get('NRCELULAR')
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
					.',IDENDERECO|IDENDERECO'
					.',DSCOMPLEMENTOENDERECO|DSCOMPLEMENTOENDERECO'
					.',NRTELEFONE|NRTELEFONE'
					.',NRCELULAR|NRCELULAR'
					.',STATIVO|STATIVO'
					.',IDUSUARIOCRIACAO|IDUSUARIOCRIACAO'
					.',DTCRIACAO|DTCRIACAO'
					.',IDUSUARIOMODIFICACAO|IDUSUARIOMODIFICACAO'
					.',DTMODIFICACAO|DTMODIFICACAO'
					;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,'Lista de Clientes Cadastrados' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'cliente.php' );

	$gride->addColumn($primaryKey,'Código');
	$gride->addColumn('NMCLIENTE','Nome');
	$gride->addColumn('NRCPFCNPJ','CPF/CNPJ');
	$gride->addColumn('NRTELEFONE','Telefone');
	$gride->addColumn('NRCELULAR','Celular');
	
	//$gride->addColumn('IDENDERECO','IDENDERECO');
	$gride->addColumn('DSCOMPLEMENTOENDERECO','Complemento');

	//$gride->addColumn('STATIVO','STATIVO');
	//$gride->addColumn('IDUSUARIOCRIACAO','IDUSUARIOCRIACAO');
	//$gride->addColumn('DTCRIACAO','DTCRIACAO');
	//$gride->addColumn('IDUSUARIOMODIFICACAO','IDUSUARIOMODIFICACAO');
	//$gride->addColumn('DTMODIFICACAO','DTMODIFICACAO');

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
					,"IDENDERECO":""
					,"DSCOMPLEMENTOENDERECO":""
					,"NRTELEFONE":""
					,"NRCELULAR":""
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
function pesquisarCepCallback(xml){
    alert( 'Evento callback do campo cep foi chamada com sucesso');
}

function pesquisarCepBeforeSend(id){
    alert( 'Evento beforeSend do campo cep '+id+' foi chamado com sucesso');
}
</script>