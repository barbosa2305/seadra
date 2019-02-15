<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDCLIENTE';
$frm = new TForm('Cliente',800,950);
$frm->setFlat(TRUE);
$frm->setMaximize(TRUE);

$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->addCpfCnpjField('NRCPFCNPJ', 'CPF/CNPJ',TRUE,null,TRUE,null,null,'CPF/CNPJ inválido.',TRUE);
$frm->addTextField('NMCLIENTE', 'Nome',255,TRUE,80);
$frm->addEmailField('DSEMAIL', 'E-mail',null,FALSE,80);
$frm->addFoneField('NRTELEFONE', 'Telefone');
$frm->addFoneField('NRCELULAR', 'Celular');
// Endereço
$frm->addCepField('DSCEP'  // id do campo
				 , 'CEP'   // label do campo
				 , TRUE    // obrigatório
				 , null	   // valor
				 , null    // Nova linha
				 , 'DSLOGRADOURO'  // campo endereço
				 , 'DSBAIRRO'  // campo bairro
				 , null       // campo cidade
				 , null       //campo cod uf 
				 , 'DSSIGLA'  // campo sig uf
				 , null       // campo numero
				 , null       // id do campo complemento
				 , 'CDMUNICIPIO_temp' // id do cod municipio
				 , null   // label sobre o campo
				 , null   // no wrap label
				 , 'myCallback'  // Js Callback
				 , null   // Js Before Send
				 , FALSE
				 , 'O CEP está incompleto.'
				 );
$frm->addTextField('DSLOGRADOURO', 'Endereço:', 60);
$frm->addTextField('DSCOMPLEMENTOENDERECO', 'Complemento',255,FALSE,80);
$frm->addTextField('DSBAIRRO', 'Bairro:', 60);
$listUF = Unidadefederativa::selectComboSiglaUf();
$frm->addSelectField('DSSIGLA', 'UF',FALSE, $listUF);
$frm->addSelectField('CDMUNICIPIO', 'Município', null, null, FALSE);
$frm->combinarSelects('DSSIGLA', 'CDMUNICIPIO', 'vw_municipios', 'DSSIGLA', 'CDMUNICIPIO', 'NMMUNICIPIO', '-- Selecione --', '0', 'Nenhum município encontrado.');

/*
$listEndereco = Endereco::selectAll();
$frm->addSelectField('IDENDERECO', 'IDENDERECO',FALSE,$listEndereco,null,null,null,null,null,null,' ',null);
$frm->addMemoField('DSCOMPLEMENTOENDERECO', 'DSCOMPLEMENTOENDERECO',255,FALSE,80,3);
*/

$frm->addTextField('STATIVO', 'Ativo ?',1,TRUE,1);

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);


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
					   ,'Lista de clientes' // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'cliente.php' );

	$gride->addColumn($primaryKey,'Código');
	$gride->addColumn('NRCPFCNPJ','CPF/CNPJ');
	$gride->addColumn('NMCLIENTE','Nome');
	$gride->addColumn('DSEMAIL','E-mail');
	$gride->addColumn('NRTELEFONE','Telefone');
	$gride->addColumn('NRCELULAR','Celular');

	$gride->addColumn('IDENDERECO','IDENDERECO');
	$gride->addColumn('DSCOMPLEMENTOENDERECO','DSCOMPLEMENTOENDERECO');

	$gride->addColumn('STATIVO','Ativo ?');


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

function myCallback(dataset){
    //console.log(jQuery("#CDMUNICIPIO_temp").val());
    jQuery("#DSSIGLA").change();
}
</script>