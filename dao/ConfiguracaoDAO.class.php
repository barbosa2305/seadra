<?php
class ConfiguracaoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idconfiguracao
									 ,dsemitente
									 ,dsenderecoemitente
									 ,dstelefoneemitente
									 from configuracao ';

	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCONFIGURACAO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSEMITENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSENDERECOEMITENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSTELEFONEEMITENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idconfiguracao = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idconfiguracao) as qtd from configuracao';
		$sql = $sql.( ($where)? ' where '.$where:'');
		$result = self::executeSql( $sql );
		return $result['QTD'][0];
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null,$where=null,$page=null,$rowsPerPage= null ){
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
	public static function insert( ConfiguracaoVO $objVo ) {
		$values = array(  $objVo->getDsemitente() 
						, $objVo->getDsenderecoemitente() 
						, $objVo->getDstelefoneemitente() 
						);
		return self::executeSql('insert into configuracao(
								 dsemitente
								,dsenderecoemitente
								,dstelefoneemitente
								) values (?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( ConfiguracaoVO $objVo ) {
		$values = array( $objVo->getDsemitente()
						,$objVo->getDsenderecoemitente()
						,$objVo->getDstelefoneemitente()
						,$objVo->getIdConfiguracao() );
		return self::executeSql('update configuracao set 
								 dsemitente = ?
								,dsenderecoemitente = ?
								,dstelefoneemitente = ?
								where idconfiguracao = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array( $id );
		return self::executeSql('delete from configuracao where idconfiguracao = ?',$values);
	}
	//--------------------------------------------------------------------------------
}
?>