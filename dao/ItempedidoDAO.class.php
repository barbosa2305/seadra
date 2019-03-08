<?php
class ItempedidoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									 idcliente
									 ,nmcliente
									 ,nrcpfcnpj
									 ,nrtelefone
									 ,nrcelular
									 ,dscep
									 ,dslogradouro
									 ,dscomplemento
									 ,dsbairro
									 ,nmmunicipio
									 ,dssigla
									 ,idpedido
									 ,dtpedido
									 ,dtpedidoformatada
                                     ,iditempedido
                                     ,@contador := @contador + 1 as nritem
                                     ,idproduto
                                     ,idprodutoformatado
									 ,nmproduto
									 ,dsunidademedida
									 ,vlprecovenda
									 ,qtitempedido
									 ,vldesconto
									 ,vltotalitem
									 ,vlpedido
									 ,vltotaldesconto
									 ,vltotal 
									 from 
									 (select @contador:=0) as zeracontador
									 ,seadra.vw_pedido_itens ' ;

	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDITEMPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
            $where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMPRODUTO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPRECOVENDA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'QTITEMPEDIDO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLDESCONTO', SqlHelper::SQL_TYPE_NUMERIC);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idItemPedido = ?';
		return self::executeSql($sql, $values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idItemPedido) as qtd from seadra.vw_pedido_itens';
		$sql = $sql.( ($where)? ' where '.$where:'');
		$result = self::executeSql( $sql );
		return $result['QTD'][0];
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null,$where=null,$page=null,$rowsPerPage=null ){
		$rowStart = PaginationSQLHelper::getRowStart($page,$rowsPerPage);
		$where = self::processWhereGridParameters($where);
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
	public static function insert( ItempedidoVO $objVo ){
		$values = array( 
						$objVo->getIdpedido() 
						,$objVo->getIdproduto() 
						,$objVo->getQtitempedido()
						,TrataDados::converteMoeda( $objVo->getVldesconto() ) 
						);
		return self::executeSql('insert into seadra.itempedido(
								idpedido
								,idproduto
								,qtitempedido
								,vldesconto
								) values (?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update( ItempedidoVO $objVo ){
		$values = array( 
						$objVo->getIdpedido()
						,$objVo->getIdproduto()
						,$objVo->getQtitempedido()
						,TrataDados::converteMoeda( $objVo->getVldesconto() ) 
						,$objVo->getIdItemPedido() 
						);
		return self::executeSql('update seadra.itempedido set 
								idpedido = ?
								,idproduto = ?
								,qtitempedido = ?
								,vldesconto = ?
								where idItemPedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array( $id );
		return self::executeSql('delete from seadra.itempedido where idItemPedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function deleteIdPedido( $id ){
		$values = array( $id );
		return self::executeSql('delete from seadra.itempedido where idPedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
}
?>