<?php
class ClienteDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
								  	 idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,dsemail
									 ,nrtelefone
									 ,nrcelular
									 ,dsobservacao
									 ,stativo
									 ,idendereco
									 ,dscep
									 ,dslogradouro
									 ,dscomplemento
									 ,dsbairro
									 ,dslocalidade
									 ,idmunicipio
									 ,cdmunicipio
									 ,nmmunicipio
									 ,idunidadefederativa
									 ,dssigla
									 ,dsunidadefederativa
									 from seadra.vw_cliente ';

	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMCLIENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCPFCNPJ', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSEMAIL', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRTELEFONE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCELULAR', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSOBSERVACAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDENDERECO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSCEP', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOGRADOURO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSCOMPLEMENTO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSBAIRRO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOCALIDADE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDMUNICIPIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMMUNICIPIO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUNIDADEFEDERATIVA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSSIGLA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSUNIDADEFEDERATIVA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idCliente = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idCliente) as qtd from seadra.vw_cliente';
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
	public static function insert( ClienteVO $objVo ){
		$values = array(  trim($objVo->getNmcliente())
						, preg_replace('/[^0-9]/','',$objVo->getNrcpfcnpj()) 
						, trim(strtolower($objVo->getDsemail()))
						, preg_replace('/[^0-9]/','',$objVo->getNrtelefone())
						, preg_replace('/[^0-9]/','',$objVo->getNrcelular()) 
						, trim($objVo->getDsobservacao())
						, $objVo->getIdusuario()
						);				
		self::executeSql('insert into seadra.cliente(
								 nmcliente
								,nrcpfcnpj
								,dsemail
								,nrtelefone
								,nrcelular
								,dsobservacao
								,idusuariocriacao
								) values (?,?,?,?,?,?,?)', $values );
		return self::getLastId('seadra.cliente','idCliente');
	}
	//--------------------------------------------------------------------------------
	public static function update ( ClienteVO $objVo ){
		$values = array(  trim($objVo->getNmcliente())
						, preg_replace('/[^0-9]/','',$objVo->getNrcpfcnpj())
						, trim(strtolower($objVo->getDsemail()))
						, preg_replace('/[^0-9]/','',$objVo->getNrtelefone())
						, preg_replace('/[^0-9]/','',$objVo->getNrcelular())
						, trim($objVo->getDsobservacao()) 
						, $objVo->getStativo()
						, $objVo->getIdusuario()
						, $objVo->getIdCliente()
					   );
		return self::executeSql('update seadra.cliente set 
								 nmcliente = ?
								,nrcpfcnpj = ?
								,dsemail = ?
								,nrtelefone = ?
								,nrcelular = ?
								,dsobservacao = ?
								,stativo = ?
								,idusuariomodificacao = ?
								where idCliente = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function updateStatus ( ClienteVO $objVo ){
		$values = array( $objVo->getStativo()
						,$objVo->getIdCliente() );
		return self::executeSql('update seadra.cliente set 
								 stativo = ?
								 where idCliente = ?',$values);
	}
}
?>