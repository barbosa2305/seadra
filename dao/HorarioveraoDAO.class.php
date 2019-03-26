<?php
class HorarioveraoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
										idhorarioverao
										,dtinicio
										,DATE_FORMAT(dtinicio,\'%d/%m/%Y %H:%i:%s\') as dtinicioformatada
										,dtfim
										,DATE_FORMAT(dtfim,\'%d/%m/%Y %H:%i:%s\') as dtfimformatada
									 from horarioverao ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDHORARIOVERAO', SqlHelper::SQL_TYPE_NUMERIC);
			if ( !empty($whereGrid['DTINICIO']) ){
				$dtInicio = DateTimeHelper::date2Mysql( $whereGrid['DTINICIO'] );
				$where = $where.( paginationSQLHelper::attributeIssetOrNotZero($whereGrid,'DTINICIO',' AND DTINICIO = \''.$dtInicio.'\' ',null) );
			}
			if ( !empty($whereGrid['DTFIM']) ){
				$dtFim = DateTimeHelper::date2Mysql( $whereGrid['DTFIM'] );
				$where = $where.( paginationSQLHelper::attributeIssetOrNotZero($whereGrid,'DTFIM',' AND DTFIM = \''.$dtFim.'\' ',null) );
			}
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		if( empty($id) || !is_numeric($id) ){
			throw new InvalidArgumentException();
		}
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idhorarioverao = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idhorarioverao) as qtd from horarioverao';
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
	public static function selectUltimoPeriodo( $orderBy=null, $where=null ) {
		$where = self::processWhereGridParameters($where);
		$sql = self::$sqlBasicSelect
		.( ($where)? ' where '.$where:'')
		.( ($orderBy) ? ' order by '.$orderBy:'')
		.( ' LIMIT 1 ');
		return self::executeSql($sql);
	}
	//--------------------------------------------------------------------------------
	public static function insert( HorarioveraoVO $objVo ) {
		$values = array(  $objVo->getDtinicio() 
						, $objVo->getDtfim() 
						);
		return self::executeSql('insert into horarioverao(
									dtinicio
									,dtfim
								) values (?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( HorarioveraoVO $objVo ) {
		$values = array( $objVo->getDtinicio()
						,$objVo->getDtfim()
						,$objVo->getIdhorarioverao() );
		return self::executeSql('update horarioverao set 
									dtinicio = ?
									,dtfim = ?
								where idhorarioverao = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from horarioverao where idhorarioverao = ?',$values);
	}
}
?>