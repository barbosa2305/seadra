<?php
class ProdutoDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idproduto
                                     ,nmproduto
                                     ,dsunidademedida
									 ,format(vlprecocusto,2,\'de_DE\') as vlprecocusto
									 ,format(vlprecovenda,2,\'de_DE\') as vlprecovenda
									 ,stativo
									 from seadra.produto ';

	private static function processWhereGridParameters( $whereGrid ){
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDPRODUTO', SqlHelper::SQL_TYPE_NUMERIC);
            $where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMPRODUTO', SqlHelper::SQL_TYPE_TEXT_LIKE);
            $where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSUNIDADEMEDIDA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPRECOCUSTO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'VLPRECOVENDA', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idProduto = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idProduto) as qtd from seadra.produto';
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
	public static function insert( ProdutoVO $objVo ){
        $values = array( trim( $objVo->getNmproduto() )
                        ,strtoupper(trim( $objVo->getDsunidademedida() ))
						,TrataDados::converteMoeda( $objVo->getVlprecocusto() ) 
						,TrataDados::converteMoeda( $objVo->getVlprecovenda() ) 
						,$objVo->getIdusuario() 
						);
		return self::executeSql('insert into seadra.produto(
                                 nmproduto
                                ,dsunidademedida
								,vlprecocusto
								,vlprecovenda
								,idusuariocriacao
								) values (?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( ProdutoVO $objVo ){
        $values = array( trim($objVo->getNmproduto())
                        ,strtoupper(trim( $objVo->getDsunidademedida() ))   
						,TrataDados::converteMoeda( $objVo->getVlprecocusto() ) 
						,TrataDados::converteMoeda( $objVo->getVlprecovenda() ) 
						,$objVo->getStativo()
						,$objVo->getIdusuario()
						,$objVo->getIdProduto() );
		return self::executeSql('update seadra.produto set 
                                 nmproduto = ?
                                ,dsunidademedida = ? 
								,vlprecocusto = ?
								,vlprecovenda = ?
								,stativo = ?
								,idusuariomodificacao = ?
								where idProduto = ?',$values );
	}
	//--------------------------------------------------------------------------------
	public static function updateStatus ( ProdutoVO $objVo ){
		$values = array( $objVo->getStativo()
						,$objVo->getIdproduto() );
		return self::executeSql('update seadra.produto set 
								 stativo = ?
								 where idProduto = ?',$values );
	}
	//--------------------------------------------------------------------------------
}
?>