<?php
class PedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idpedido
									 ,idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,stativo
									 ,DATE_FORMAT(dtpedido,\'%d/%m/%Y\') as dtpedido
									 from vw_pedido_cliente ';
	//--------------------------------------------------------------------------------
	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDCLIENTE', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMCLIENTE', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NRCPFCNPJ', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			if ( !empty($whereGrid['DTPEDIDO']) ){
				$dtPedido = DateTimeHelper::date2Mysql( $whereGrid['DTPEDIDO'] );
				$where = $where.( paginationSQLHelper::attributeIssetOrNotZero($whereGrid,'DTPEDIDO',' AND DTPEDIDO = \''.$dtPedido.'\' ',null) );
			}
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	private static function processWhereRelOrcamentoParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STCLIENTEATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STPRODUTOATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idPedido = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idpedido) as qtd from vw_pedido_cliente ';
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
	public static function insert( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente() 
						 ,$objVo->getDtpedido() 
						 ,$objVo->getIdusuario() 
						);
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'insert into pedido(
						idcliente
						,dtpedido
						,idusuariocriacao
					) values (?,?,?)' ;
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function update( PedidoVO $objVo ){
		$values = array( $objVo->getIdcliente()
						 ,$objVo->getDtpedido()
						 ,$objVo->getIdusuario()
						 ,$objVo->getIdPedido() 
						);
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'update pedido set 
						idcliente = ?
						,dtpedido = ?
						,idusuariomodificacao = ?
					where idpedido = ?' ;
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = null;
		$values = array( $id );
		self::beginTransaction();
		$result = ItempedidoDAO::deleteIdPedido( $id );
		$result = self::executeSql( 'delete from pedido where idpedido = ?',$values );
		self::commit();
		$erro =self::getError();
		if ( $erro ) {
			self::rollBack();
			throw new Exception( $erro );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
}
?>