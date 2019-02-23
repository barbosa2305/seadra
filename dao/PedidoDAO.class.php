<?php
class PedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido
									 ,idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,dtpedido
									 ,vltotal
									 ,vldesconto
									 ,vlpago
									 from seadra.vw_pedido ';

	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMCLIENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCPFCNPJ', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTPEDIDO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLTOTAL', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLDESCONTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPAGO', SqlHelper::SQL_TYPE_NUMERIC);
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
		$sql = 'select count(idPedido) as qtd from seadra.pedido';
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
	public static function insert( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente() 
						 ,$objVo->getDtpedido() 
						 ,$objVo->getVltotal() 
						 ,$objVo->getVldesconto() 
						 ,$objVo->getVlpago() 
						 ,$objVo->getIdusuario() 
						);
		return self::executeSql( 'insert into seadra.pedido(
								  idcliente
								 ,dtpedido
							 	 ,vltotal
								 ,vldesconto
								 ,vlpago
								 ,idusuariocriacao
								 ) values (?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente()
						 ,$objVo->getDtpedido()
						 ,$objVo->getVltotal()
						 ,$objVo->getVldesconto()
						 ,$objVo->getVlpago()
						 ,$objVo->getIdusuario()
						 ,$objVo->getIdPedido() 
					    );
		return self::executeSql( 'update seadra.pedido set 
								  idcliente = ?
							  	 ,dtpedido = ?
								 ,vltotal = ?
								 ,vldesconto = ?
								 ,vlpago = ?
								 ,idusuariomodificacao = ?
								 where idPedido = ?',$values );
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = null;
		$values = array( $id );
		self::beginTransaction();
		$result = self::executeSql( 'delete from seadra.itempedido where idPedido = ?',$values );
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