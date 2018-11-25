<?php
class ClienteDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,nrtelefone
									 ,nrcelular
									 ,idendereco
									 ,dscomplementoendereco
									 ,stativo
									 ,idusuariocriacao
									 ,dtcriacao
									 ,idusuariomodificacao
									 ,dtmodificacao
									 from seadra.cliente ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMCLIENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCPFCNPJ', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRTELEFONE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCELULAR', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDENDERECO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSCOMPLEMENTOENDERECO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
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
		$sql = self::$sqlBasicSelect.' where idCliente = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idCliente) as qtd from seadra.cliente';
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
	public static function insert( ClienteVO $objVo ) {
		$values = array(  $objVo->getNmcliente() 
						, $objVo->getNrcpfcnpj() 
						, $objVo->getNrtelefone() 
						, $objVo->getNrcelular() 
						, $objVo->getIdendereco() 
						, $objVo->getDscomplementoendereco() 
						, $objVo->getStativo() 
						, $objVo->getIdusuariocriacao() 
						, $objVo->getDtcriacao() 
						, $objVo->getIdusuariomodificacao() 
						, $objVo->getDtmodificacao() 
						);
		return self::executeSql('insert into seadra.cliente(
								 nmcliente
								,nrcpfcnpj
								,nrtelefone
								,nrcelular
								,idendereco
								,dscomplementoendereco
								,stativo
								,idusuariocriacao
								,dtcriacao
								,idusuariomodificacao
								,dtmodificacao
								) values (?,?,?,?,?,?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( ClienteVO $objVo ) {
		$values = array( $objVo->getNmcliente()
						,$objVo->getNrcpfcnpj()
						,$objVo->getNrtelefone()
						,$objVo->getNrcelular()
						,$objVo->getIdendereco()
						,$objVo->getDscomplementoendereco()
						,$objVo->getStativo()
						,$objVo->getIdusuariocriacao()
						,$objVo->getDtcriacao()
						,$objVo->getIdusuariomodificacao()
						,$objVo->getDtmodificacao()
						,$objVo->getIdCliente() );
		return self::executeSql('update seadra.cliente set 
								 nmcliente = ?
								,nrcpfcnpj = ?
								,nrtelefone = ?
								,nrcelular = ?
								,idendereco = ?
								,dscomplementoendereco = ?
								,stativo = ?
								,idusuariocriacao = ?
								,dtcriacao = ?
								,idusuariomodificacao = ?
								,dtmodificacao = ?
								where idCliente = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.cliente where idCliente = ?',$values);
	}
}
?>