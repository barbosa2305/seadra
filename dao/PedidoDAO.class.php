<?php
class PedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido
									 ,idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,stativo
									 ,DATE_FORMAT(dtpedido,\'%d/%m/%Y\') as dtpedido
									 from seadra.vw_pedido_cliente ';
	//--------------------------------------------------------------------------------
	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMCLIENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCPFCNPJ', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			if ( !empty($whereGrid['DTPEDIDO']) ){
				$dtPedido = DateTimeHelper::date2Mysql( $whereGrid['DTPEDIDO'] );
				$where = $where.( paginationSQLHelper::attributeIssetOrNotZero($whereGrid,'DTPEDIDO',' AND DTPEDIDO = \''.$dtPedido.'\' ',null) );
			}
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	private static function processWhereRelOrcamentoParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STCLIENTEATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STPRODUTOATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idPedido = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idPedido) as qtd from seadra.vw_pedido_cliente ';
		$sql = $sql.( ($where)? ' where '.$where:'');
		$result = self::executeSql( $sql );
		return $result['QTD'][0];
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null,$where=null,$page=null,$rowsPerPage=null ){
		$rowStart = PaginationSQLHelper::getRowStart( $page,$rowsPerPage );
		$where = self::processWhereGridParameters( $where );
		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'')
		.( ' LIMIT '.$rowStart.','.$rowsPerPage);
		return self::executeSql( $sql );
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null,$where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'');
		return self::executeSql( $sql );
	}
	//--------------------------------------------------------------------------------
	public static function selectRelOrcamento( $orderBy=null,$where=null ){
		$where = self::processWhereRelOrcamentoParameters( $where );
		$sql = 'select 
				idcliente
				,nmcliente
				,nrcpfcnpj
				,nrtelefone
				,nrcelular
				,stclienteativo
				,dscep
				,dslogradouro
				,dscomplemento
				,dsbairro
				,nmmunicipio
				,dssigla
				,idpedido
				,dtpedido
				,format(vltotal,2,\'de_DE\') as vltotal
				,format(vldesconto,2,\'de_DE\') as vldesconto
                ,format(vlpago,2,\'de_DE\') as vlpago
                ,@contador := @contador + 1 as nritem
				,lpad(idproduto, 5, "0") as idproduto
                ,nmproduto
                ,dsunidademedida
				,format(vlprecovenda,2,\'de_DE\') as vlprecovenda
				,stprodutoativo
                ,qtitempedido
                ,format((vlprecovenda * qtitempedido),2,\'de_DE\') as vltotalitem 
                from (select @contador:=0) as zeracontador
                ,seadra.vw_rel_orcamento '	
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'');
		return self::executeSql( $sql );
	}
	//--------------------------------------------------------------------------------
	public static function insert( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente() 
						 ,$objVo->getDtpedido() 
						 ,$objVo->getIdusuario() 
						);
		return self::executeSql( 'insert into seadra.pedido(
								  idcliente
								 ,dtpedido
								 ,idusuariocriacao
								 ) values (?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente()
						 ,$objVo->getDtpedido()
						 ,$objVo->getIdusuario()
						 ,$objVo->getIdPedido() 
					    );
		return self::executeSql( 'update seadra.pedido set 
								  idcliente = ?
							  	 ,dtpedido = ?
								 ,idusuariomodificacao = ?
								 where idPedido = ?',$values );
	}
	//--------------------------------------------------------------------------------
	public static function updateDesconto( $idPedido,$vlDesconto ){
		$values = array( TrataDados::converteMoeda( $vlDesconto )
						,$idPedido
					   );
		$sql = 'update seadra.pedido 
			    set vlDesconto = ?
				where idPedido = ?';				
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = null;
		$values = array( $id );
		self::beginTransaction();
		$result = ItempedidoDAO::deleteIdPedido( $id );
		$result = self::executeSql( 'delete from seadra.pedido where idPedido = ?',$values );
		self::commit();
		$erro =self::getError();
		if ( $erro ) {
			self::rollBack();
			throw new Exception( $erro );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
}
?>