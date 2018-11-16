<?php
class PedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido
									 ,idcliente
									 ,dtpedido
									 ,vltotal
									 ,vldesconto
									 ,vlpago
									 ,idusuariocriacao
									 ,dtcriacao
									 ,idusuariomodificacao
									 ,dtmodificacao
									 from seadra.pedido ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTPEDIDO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLTOTAL', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLDESCONTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPAGO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUSUARIOCRIACAO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTCRIACAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUSUARIOMODIFICACAO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTMODIFICACAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idPedido = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idPedido) as qtd from seadra.pedido';
		$sql = $sql.( ($where)? ' where '.$where:'');
		$result = self::executeSql($sql);
		return $result['QTD'][0];
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null ) {
		$rowStart = PaginationSQLHelper::getRowStart($page,$rowsPerPage);
		$where = self::processWhereGridParameters($where);

		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'')
		.( ' LIMIT '.$rowStart.','.$rowsPerPage);

		$result = self::executeSql($sql);
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ) {
		$where = self::processWhereGridParameters($where);
		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'');

		$result = self::executeSql($sql);
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function insert( PedidoVO $objVo ) {
		$values = array(  $objVo->getIdcliente() 
						, $objVo->getDtpedido() 
						, $objVo->getVltotal() 
						, $objVo->getVldesconto() 
						, $objVo->getVlpago() 
						, $objVo->getIdusuariocriacao() 
						, $objVo->getDtcriacao() 
						, $objVo->getIdusuariomodificacao() 
						, $objVo->getDtmodificacao() 
						);
		return self::executeSql('insert into seadra.pedido(
								 idcliente
								,dtpedido
								,vltotal
								,vldesconto
								,vlpago
								,idusuariocriacao
								,dtcriacao
								,idusuariomodificacao
								,dtmodificacao
								) values (?,?,?,?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( PedidoVO $objVo ) {
		$values = array( $objVo->getIdcliente()
						,$objVo->getDtpedido()
						,$objVo->getVltotal()
						,$objVo->getVldesconto()
						,$objVo->getVlpago()
						,$objVo->getIdusuariocriacao()
						,$objVo->getDtcriacao()
						,$objVo->getIdusuariomodificacao()
						,$objVo->getDtmodificacao()
						,$objVo->getIdPedido() );
		return self::executeSql('update seadra.pedido set 
								 idcliente = ?
								,dtpedido = ?
								,vltotal = ?
								,vldesconto = ?
								,vlpago = ?
								,idusuariocriacao = ?
								,dtcriacao = ?
								,idusuariomodificacao = ?
								,dtmodificacao = ?
								where idPedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.pedido where idPedido = ?',$values);
	}
}
?>