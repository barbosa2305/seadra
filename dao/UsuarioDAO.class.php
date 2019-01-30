<?php
class UsuarioDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idusuario
									 ,nmusuario
									 ,dslogin
									 ,dssenha
									 ,stativo
									 ,dtcriacao
									 ,dtmodificacao
									 from seadra.usuario ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUSUARIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMUSUARIO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOGIN', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSSENHA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTCRIACAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DTMODIFICACAO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ) {
		$values = array($id);
		$sql = self::$sqlBasicSelect.' where idUsuario = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectByLogin( $login ) {
		if( empty($login) || !is_string($login) ){
			throw new InvalidArgumentException();
		}
		$values = array($login);
		$sql = self::$sqlBasicSelect.' where dslogin = ?';
		$result = self::executeSql($sql, $values );
		return $result;
	}	
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters($where);
		$sql = 'select count(idUsuario) as qtd from seadra.usuario';
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
	public static function insert( UsuarioVO $objVo ) {
		$values = array(  $objVo->getNmusuario() 
						, $objVo->getDslogin() 
						, $objVo->getDssenha() 
						, $objVo->getStativo() 
						, $objVo->getDtcriacao() 
						, $objVo->getDtmodificacao() 
						);
		return self::executeSql('insert into seadra.usuario(
								 nmusuario
								,dslogin
								,dssenha
								,stativo
								,dtcriacao
								,dtmodificacao
								) values (?,?,?,?,?,?)', $values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( UsuarioVO $objVo ) {
		$values = array( $objVo->getNmusuario()
						,$objVo->getDslogin()
						,$objVo->getDssenha()
						,$objVo->getStativo()
						,$objVo->getDtcriacao()
						,$objVo->getDtmodificacao()
						,$objVo->getIdUsuario() );
		return self::executeSql('update seadra.usuario set 
								 nmusuario = ?
								,dslogin = ?
								,dssenha = ?
								,stativo = ?
								,dtcriacao = ?
								,dtmodificacao = ?
								where idUsuario = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function updateSenha ( UsuarioVO $objVo ) {
		$values = array( $objVo->getDssenha()
                        ,$objVo->getIdusuario() );
		return self::executeSql('update seadra.usuario set dssenha = ? where idUsuario = ?',$values);
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$values = array($id);
		return self::executeSql('delete from seadra.usuario where idUsuario = ?',$values);
	}
}
?>