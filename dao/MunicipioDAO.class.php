<?php
class MunicipioDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idmunicipio
									 ,cdmunicipio
									 ,nmmunicipio
									 ,idunidadefederativa
									 ,stativo
									 from seadra.municipio ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDMUNICIPIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'CDMUNICIPIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMMUNICIPIO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUNIDADEFEDERATIVA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idMunicipio = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idMunicipio) as qtd from seadra.municipio';
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
	public static function selectByMunicipioSigla( $nmMunicipio,$dsSigla ) {
		$values = array( $nmMunicipio,$dsSigla );
		$sql = 'select
					idunidadefederativa
					,dssigla
					,dsunidadefederativa
					,idmunicipio
					,cdmunicipio
					,nmmunicipio
				from seadra.vw_municipio 
				where nmmunicipio = ? 
				and dssigla = ? ';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function insert( MunicipioVO $objVo ) {
		$values = array(  $objVo->getCdmunicipio() 
						, $objVo->getNmmunicipio() 
						, $objVo->getIdunidadefederativa() 
						, $objVo->getStativo() 
						);
		return self::executeSql('insert into seadra.municipio(
								 cdmunicipio
								,nmmunicipio
								,idunidadefederativa
								,stativo
								) values (?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( MunicipioVO $objVo ) {
		$values = array( $objVo->getCdmunicipio()
						,$objVo->getNmmunicipio()
						,$objVo->getIdunidadefederativa()
						,$objVo->getStativo()
						,$objVo->getIdMunicipio() );
		return self::executeSql('update seadra.municipio set 
								 cdmunicipio = ?
								,nmmunicipio = ?
								,idunidadefederativa = ?
								,stativo = ?
								where idMunicipio = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.municipio where idMunicipio = ?',$values);
	}
}
?>