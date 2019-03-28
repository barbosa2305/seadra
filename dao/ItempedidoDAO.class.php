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
									 ,dsobservacao
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
									 ,qtitempedidoformatado
									 ,vltotalitem
									 ,vldesconto
									 ,vldescontoformatado
									 ,vltotalitemcomdesconto
									 ,vlpedido
									 ,vltotaldesconto
									 ,vltotal 
									 from 
									 (select @contador:=0) as zeracontador
									 ,vw_pedido_itens ' ;

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
		$sql = self::$sqlBasicSelect.' where iditempedido = ?';
		return self::executeSql($sql, $values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idItemPedido) as qtd from vw_pedido_itens';
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
						,TrataDados::converteMoeda( $objVo->getQtitempedido() )
						,TrataDados::converteMoeda( $objVo->getVldesconto() ) 
						);
		return self::executeSql('insert into itempedido(
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
						,TrataDados::converteMoeda( $objVo->getQtitempedido() )
						,TrataDados::converteMoeda( $objVo->getVldesconto() ) 
						,$objVo->getIdItemPedido() 
						);
		return self::executeSql('update itempedido set 
								idpedido = ?
								,idproduto = ?
								,qtitempedido = ?
								,vldesconto = ?
								where iditempedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array( $id );
		return self::executeSql('delete from itempedido where iditempedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function deleteIdPedido( $id ){
		$values = array( $id );
		return self::executeSql('delete from itempedido where idpedido = ?',$values);
	}
	//--------------------------------------------------------------------------------
}
?>