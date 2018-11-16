<?php
class ProdutoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idproduto
									 ,nmproduto
									 ,vlprecocusto
									 ,vlprecovenda
									 ,stativo
									 ,idusuariocriacao
									 ,dtcriacao
									 ,idusuariomodificacao
									 ,dtmodificacao
									 from seadra.produto ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMPRODUTO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPRECOCUSTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPRECOVENDA', SqlHelper::SQL_TYPE_NUMERIC);
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
		$sql = self::$sqlBasicSelect.' where idProduto = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idProduto) as qtd from seadra.produto';
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
	public static function insert( ProdutoVO $objVo ) {
		$values = array(  $objVo->getNmproduto() 
						, $objVo->getVlprecocusto() 
						, $objVo->getVlprecovenda() 
						, $objVo->getStativo() 
						, $objVo->getIdusuariocriacao() 
						, $objVo->getDtcriacao() 
						, $objVo->getIdusuariomodificacao() 
						, $objVo->getDtmodificacao() 
						);
		return self::executeSql('insert into seadra.produto(
								 nmproduto
								,vlprecocusto
								,vlprecovenda
								,stativo
								,idusuariocriacao
								,dtcriacao
								,idusuariomodificacao
								,dtmodificacao
								) values (?,?,?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( ProdutoVO $objVo ) {
		$values = array( $objVo->getNmproduto()
						,$objVo->getVlprecocusto()
						,$objVo->getVlprecovenda()
						,$objVo->getStativo()
						,$objVo->getIdusuariocriacao()
						,$objVo->getDtcriacao()
						,$objVo->getIdusuariomodificacao()
						,$objVo->getDtmodificacao()
						,$objVo->getIdProduto() );
		return self::executeSql('update seadra.produto set 
								 nmproduto = ?
								,vlprecocusto = ?
								,vlprecovenda = ?
								,stativo = ?
								,idusuariocriacao = ?
								,dtcriacao = ?
								,idusuariomodificacao = ?
								,dtmodificacao = ?
								where idProduto = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.produto where idProduto = ?',$values);
	}
}
?>