<?php
defined('APLICATIVO') or die();

$primaryKey = 'IDCLIENTE';
$frm = new TForm('Cliente',662,840);
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$frm->addHiddenField( 'BUSCAR' ); //Campo oculto para buscas
$frm->addHiddenField( $primaryKey );   // coluna chave da tabela

$frm->setColumns('64');
$frm->addGroupField('gpDadosBasicos','Dados básicos',null,400);
	$frm->addCpfCnpjField('NRCPFCNPJ', 'CPF/CNPJ:',TRUE,null,TRUE,null,null,'CPF/CNPJ inválido.',TRUE);
	$frm->addTextField('NMCLIENTE', 'Nome:',255,TRUE,49,null,TRUE);
	$frm->addEmailField('DSEMAIL', 'E-mail:',null,FALSE,49,TRUE);
	$frm->addFoneField('NRTELEFONE', 'Telefone:',null,TRUE);

	//$frm->addMaskField('C2', 'Celular 2:', FALSE, '999999999999999',TRUE)->addEvent('onkeypress','text_change(this)');
	//$frm->addNumberField('C2','Cel:',20,FALSE,0,TRUE)->addEvent('onkeypress','text_change(this)');
	
	$frm->addFoneField('NRCELULAR', 'Celular:',null,TRUE);
	$ativo = array( 'S'=>'Sim','N'=>'Não' );
	$frm->addSelectField('STATIVO', 'Ativo ?',FALSE,$ativo,null,null,null,null,null,null,null,null);
$frm->closeGroup();
	
$frm->setColumns('78');
$frm->addGroupField('gpEndereco','Endereço',null,400,FALSE);	
	$frm->addHiddenField('IDENDERECO');
	// Endereço
	$frm->addCepField('DSCEP'  // id do campo
					, 'CEP:'   // label do campo
					, FALSE    // obrigatório
					, null	   // valor
					, null    // Nova linha
					, 'DSLOGRADOURO'  // campo endereço
					, 'DSBAIRRO'  // campo bairro
					, 'DSLOCALIDADE' // campo cidade
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
					)->addEvent('onchange','select_change(this)');
	$frm->addHiddenField('DSLOCALIDADE');
	$frm->addTextField('DSLOGRADOURO', 'Endereço:', 255,null,47,null,TRUE);
	$frm->addTextField('DSCOMPLEMENTO', 'Complemento:',255,null,47,null,TRUE);
	$frm->addTextField('DSBAIRRO', 'Bairro:', 255,null,47,null,TRUE);
	$listUF = Unidadefederativa::selectComboSiglaUf();
	$frm->addSelectField('DSSIGLA', 'UF:',FALSE, $listUF,TRUE);
	$frm->addSelectField('CDMUNICIPIO', 'Município:', null, null, TRUE);
	$frm->combinarSelects('DSSIGLA', 'CDMUNICIPIO', 'vw_municipio', 'DSSIGLA', 'CDMUNICIPIO', 'NMMUNICIPIO', '-- selecione --', '0', 'Nenhum município encontrado.');
$frm->closeGroup();

$frm->setColumns('64');
$frm->addGroupField('gpObservacao','Observações',null,823);
	$frm->addMemoField('DSOBSERVACAO',null,2000,null,122,null,TRUE);
$frm->closeGroup();

$frm->addHtmlField('html1', '* Preenchimento obrigatório.', null, null, null, null)->setCss('color', 'red');

$frm->addButton('Buscar', null, 'btnBuscar', 'buscar()', null, TRUE, FALSE);
$frm->addButton('Salvar', null, 'Salvar', null, null, FALSE, FALSE);
$frm->addButton('Limpar', null, 'Limpar', null, null, FALSE, FALSE);
$frm->addButton('Ir para pedido','Pedido',null,null,null,FALSE,TRUE);

$acao = isset($acao) ? $acao : null;
switch( $acao ) {
	//--------------------------------------------------------------------------------
	case 'Limpar':
		$frm->clearFields();
	break;
	case 'Salvar':
		try{
			if ( $frm->validate() ){
				$vo = new ClienteVO();
				$frm->setVo( $vo );
				$vo->setIdusuario( Acesso::getUserId() );
				$resultado = Cliente::save( $vo );
				if ( $resultado ){
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
			$resultado = Cliente::delete( $id );;
			if ( $resultado ){
				$frm->setMessage( Mensagem::OPERACAO_COM_SUCESSO );
				$frm->clearFields();
			} else {
				$frm->setMessage( $resultado );
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
	case 'Pedido':
		$frm->setFieldValue( 'BUSCAR',null );
		$frm->setFieldValue( 'IDCLIENTE',null );
		$frm->setFieldValue( 'NMCLIENTE',null );
		$frm->redirect( 'pedido.php',null,TRUE );
	break;
	//--------------------------------------------------------------------------------
}

function getWhereGridParameters(&$frm){
	$retorno = null;
	if($frm->get('BUSCAR') == 1 ){
		$retorno = array(
				'IDCLIENTE'=>$frm->get('IDCLIENTE')
				,'NMCLIENTE'=>$frm->get('NMCLIENTE')
				,'NRCPFCNPJ'=>$frm->get('NRCPFCNPJ')
				,'DSEMAIL'=>$frm->get('DSEMAIL')
				,'NRTELEFONE'=>preg_replace('/[^0-9]/','',$frm->get('NRTELEFONE'))
				,'NRCELULAR'=>preg_replace('/[^0-9]/','',$frm->get('NRCELULAR')) 
				,'IDENDERECO'=>$frm->get('IDENDERECO')
				,'DSCEP'=> preg_replace('/[^0-9]/','',$frm->get('DSCEP'))
				,'DSLOGRADOURO'=>$frm->get('DSLOGRADOURO')
				,'DSCOMPLEMENTO'=>$frm->get('DSCOMPLEMENTO')
				,'DSBAIRRO'=>$frm->get('DSBAIRRO')
				,'DSLOCALIDADE'=>$frm->get('DSLOCALIDADE')
				,'IDMUNICIPIO'=>$frm->get('IDMUNICIPIO')
				,'CDMUNICIPIO'=>$frm->get('CDMUNICIPIO')
				,'NMMUNICIPIO'=>$frm->get('NMMUNICIPIO')
				,'DSSIGLA'=>$frm->get('DSSIGLA')
				,'STATIVO'=>$frm->get('STATIVO')
				,'DSOBSERVACAO'=>$frm->get('DSOBSERVACAO')

		);
	}
	return $retorno;
}

if( isset( $_REQUEST['ajax'] )  && $_REQUEST['ajax'] ) {
	$maxRows = 10; // ROWS_PER_PAGE;
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
					.',DSCEP|DSCEP'
					.',DSLOGRADOURO|DSLOGRADOURO'
					.',DSCOMPLEMENTO|DSCOMPLEMENTO'
					.',DSBAIRRO|DSBAIRRO'
					.',DSLOCALIDADE|DSLOCALIDADE'
					.',IDMUNICIPIO|IDMUNICIPIO'
					.',CDMUNICIPIO|CDMUNICIPIO'
					.',NMMUNICIPIO|NMMUNICIPIO'
					.',DSSIGLA|DSSIGLA'
					.',STATIVO|STATIVO' 
					.',DSOBSERVACAO|DSOBSERVACAO'
					;
	$tituloGride = 'Lista de clientes -'.' Quantidade: '.$realTotalRowsSqlPaginator;
	$gride = new TGrid( 'gd'                        // id do gride
					   ,$tituloGride // titulo do gride
					   );
	$gride->addKeyField( $primaryKey ); // chave primaria
	$gride->setData( $dados ); // array de dados
	$gride->setRealTotalRowsSqlPaginator( $realTotalRowsSqlPaginator );
	$gride->setMaxRows( $maxRows );
	$gride->setUpdateFields($mixUpdateFields);
	$gride->setUrl( 'cliente.php' );
	$gride->setZebrarColors( '#ffffff','#ffffff' );

	$gride->addColumn($primaryKey,'Código',null,'center');
	$gride->addColumn('NRCPFCNPJ','CPF/CNPJ',null,'center');
	$gride->addColumnCompact('NMCLIENTE','Nome',null,null,50);
	$gride->addColumn('DSEMAIL','E-mail');
	$gride->addColumn('NRTELEFONE','Telefone');
	$gride->addColumn('NRCELULAR','Celular');
	$gride->addColumn('DSATIVO','Ativo ?',null,'center');

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
						,"DSCEP":""
						,"DSLOGRADOURO":""
						,"DSCOMPLEMENTO":""
						,"DSBAIRRO":""
						,"DSLOCALIDADE":""
						,"IDMUNICIPIO":""
						,"CDMUNICIPIO":""
						,"NMMUNICIPIO":""
						,"DSSIGLA":""
						,"STATIVO":""
						,"DSOBSERVACAO":""
						};
		fwGetGrid('cliente.php','gride',Parameters,true);
	}
	function buscar() {
		jQuery("#BUSCAR").val(1);
		init();
	}
	function myCallback(dataset){
		console.log(jQuery("#CDMUNICIPIO_temp").val());
		jQuery("#DSSIGLA").change();
	}
	function select_change(e) {   
		jQuery("#DSCOMPLEMENTO").val("");
		jQuery("#CDMUNICIPIO").html('<option value="">-- selecione --</option>');
	}
	function text_change(e) {
		var options = {
						onKeyPress: function (phone, e, field, options) {
								var masks = ['(00) 0000-00000', '(00) 00000-0000'];
								var mask = (phone.length > 14) ? masks[1] : masks[0];
								$('#C2').mask(mask, options);
					  }
		};

		jQuery('#C2').mask('(00) 0000-00000', options);
	}
</script>