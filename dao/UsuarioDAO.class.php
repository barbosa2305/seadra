<?php
class UsuarioDAO extends TPDOConnection {

	private static $sqlBasicSelect = 'select
									  idusuario
									 ,nmusuario
									 ,dslogin
									 ,dssenha
									 ,tpgrupo
									 ,stativo
									 from usuario ';

	private static function processWhereGridParameters( $whereGrid ) {
		$result = $whereGrid;
		if ( is_array($whereGrid) ){
			$where = ' 1=1 ';
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'IDUSUARIO', SqlHelper::SQL_TYPE_NUMERIC);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'NMUSUARIO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSLOGIN', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'DSSENHA', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'TPGRUPO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$where = SqlHelper::getAtributeWhereGridParameters($where, $whereGrid, 'STATIVO', SqlHelper::SQL_TYPE_TEXT_LIKE);
			$result = $where;
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$values = array( $id );
		$sql = self::$sqlBasicSelect.' where idusuario = ?';
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function selectByLoginAtivo( $login ){
		if( empty($login) || !is_string($login) ){
			throw new InvalidArgumentException('Login não informado.');
		}
		$values = array( $login );
		$sql = self::$sqlBasicSelect.' where dslogin = ? and stativo = \'S\'';
		$result = self::executeSql( $sql,$values );
		return $result;
	}	
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$where = self::processWhereGridParameters( $where );
		$sql = 'select count(idusuario) as qtd from usuario';
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
	public static function insert( UsuarioVO $objVo ){
		$values = array( $objVo->getNmusuario() 
						,strtolower(trim($objVo->getDslogin()))
						,$objVo->getDssenha()
						,$objVo->getTpgrupo()
						);
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'insert into usuario(
						nmusuario
						,dslogin
						,dssenha
						,tpgrupo
					) values (?,?,?,?)' ;
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function update ( UsuarioVO $objVo ){
		$values = array( $objVo->getNmusuario()
						,strtolower(trim($objVo->getDslogin()))
						,$objVo->getTpgrupo()
						,$objVo->getStativo()
						,$objVo->getIdUsuario() );
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'update usuario set 
						nmusuario = ?
						,dslogin = ?
						,tpgrupo = ?
						,stativo = ?
					where idusuario = ?' ;						
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function updatePassword ( UsuarioVO $objVo ){
		$values = array( $objVo->getDssenha()
						,$objVo->getIdusuario() );
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'update usuario set 
						dssenha = ? 
					where idusuario = ?' ;
		return self::executeSql( $sql,$values );
	}
	//--------------------------------------------------------------------------------
	public static function updateStatus ( UsuarioVO $objVo ){
		$values = array( $objVo->getStativo()
						,$objVo->getIdusuario() );
		$sql = 'SET time_zone = "'.Timezone::get().'";'.PHP_EOL;
		$sql = $sql.'update usuario set 
						stativo = ? 
					where idusuario = ?' ;
		return self::executeSql( $sql,$values );
	}
}
?>