<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 1.0.0
 * FormDin Version: 4.2.12-alpha
 * 
 * System seadra created in: 2019-02-15 23:59:31
 */

class Cliente {


	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$result = ClienteDAO::selectById( $id );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$result = ClienteDAO::selectCount( $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null){
		$result = ClienteDAO::selectAllPagination( $orderBy, $where, $page,  $rowsPerPage );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ){
		$result = ClienteDAO::selectAll( $orderBy, $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function save( ClienteVO $objVo ){
		$result = null;
		if( $objVo->getIdcliente() ) {
			$result = ClienteDAO::update( $objVo );
		} else {
			$result = ClienteDAO::insert( $objVo );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = ClienteDAO::delete( $id );
		return $result;
	}

}
?>