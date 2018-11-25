<?php
class UnidadefederativaDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idunidadefederativa
									 ,dssigla
									 ,dsnome
									 from seadra.unidadefederativa ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUNIDADEFEDERATIVA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSSIGLA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSNOME', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idUnidadeFederativa = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idUnidadeFederativa) as qtd from seadra.unidadefederativa';
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
	public static function insert( UnidadefederativaVO $objVo ) {
		$values = array(  $objVo->getDssigla() 
						, $objVo->getDsnome() 
						);
		return self::executeSql('insert into seadra.unidadefederativa(
								 dssigla
								,dsnome
								) values (?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( UnidadefederativaVO $objVo ) {
		$values = array( $objVo->getDssigla()
						,$objVo->getDsnome()
						,$objVo->getIdUnidadeFederativa() );
		return self::executeSql('update seadra.unidadefederativa set 
								 dssigla = ?
								,dsnome = ?
								where idUnidadeFederativa = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.unidadefederativa where idUnidadeFederativa = ?',$values);
	}
}
?>