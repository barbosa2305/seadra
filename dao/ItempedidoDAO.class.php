<?php
class ItempedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  iditempedido
									 ,idpedido
									 ,idpedido
									 ,idproduto
									 ,idproduto
									 ,qtitempedido
									 from seadra.itempedido ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDITEMPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'QTITEMPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idItemPedido = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idItemPedido) as qtd from seadra.itempedido';
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
	public static function insert( ItempedidoVO $objVo ) {
		$values = array(  $objVo->getIdpedido() 
						, $objVo->getIdpedido() 
						, $objVo->getIdproduto() 
						, $objVo->getIdproduto() 
						, $objVo->getQtitempedido() 
						);
		return self::executeSql('insert into seadra.itempedido(
								 idpedido
								,idpedido
								,idproduto
								,idproduto
								,qtitempedido
								) values (?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( ItempedidoVO $objVo ) {
		$values = array( $objVo->getIdpedido()
						,$objVo->getIdpedido()
						,$objVo->getIdproduto()
						,$objVo->getIdproduto()
						,$objVo->getQtitempedido()
						,$objVo->getIdItemPedido() );
		return self::executeSql('update seadra.itempedido set 
								 idpedido = ?
								,idpedido = ?
								,idproduto = ?
								,idproduto = ?
								,qtitempedido = ?
								where idItemPedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.itempedido where idItemPedido = ?',$values);
	}
}
?>