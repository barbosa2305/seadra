<?php
class EnderecoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idendereco
									 ,dscep
									 ,dslogradouro
									 ,dsbairro
									 ,dslocalidade
									 ,idmunicipio
									 from seadra.endereco ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDENDERECO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSCEP', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOGRADOURO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSBAIRRO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOCALIDADE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDMUNICIPIO', SqlHelper::SQL_TYPE_NUMERIC);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idEndereco = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idEndereco) as qtd from seadra.endereco';
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
	public static function insert( EnderecoVO $objVo ) {
		$values = array(  $objVo->getDscep() 
						, $objVo->getDslogradouro() 
						, $objVo->getDsbairro() 
						, $objVo->getDslocalidade() 
						, $objVo->getIdmunicipio() 
						);
		return self::executeSql('insert into seadra.endereco(
								 dscep
								,dslogradouro
								,dsbairro
								,dslocalidade
								,idmunicipio
								) values (?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( EnderecoVO $objVo ) {
		$values = array( $objVo->getDscep()
						,$objVo->getDslogradouro()
						,$objVo->getDsbairro()
						,$objVo->getDslocalidade()
						,$objVo->getIdmunicipio()
						,$objVo->getIdEndereco() );
		return self::executeSql('update seadra.endereco set 
								 dscep = ?
								,dslogradouro = ?
								,dsbairro = ?
								,dslocalidade = ?
								,idmunicipio = ?
								where idEndereco = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.endereco where idEndereco = ?',$values);
	}
}
?>