<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 0.9.0
 * FormDin Version: 4.2.6-alpha
 * 
 * System seadra created in: 2018-11-25 11:20:25
 */

class Unidadefederativa {


	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$result = UnidadefederativaDAO::selectById( $id );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$result = UnidadefederativaDAO::selectCount( $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null){
		$result = UnidadefederativaDAO::selectAllPagination( $orderBy, $where, $page,  $rowsPerPage );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ){
		$result = UnidadefederativaDAO::selectAll( $orderBy, $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function save( UnidadefederativaVO $objVo ){
		$result = null;
		if( $objVo->getIdunidadefederativa() ) {
			$result = UnidadefederativaDAO::update( $objVo );
		} else {
			$result = UnidadefederativaDAO::insert( $objVo );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = UnidadefederativaDAO::delete( $id );
		return $result;
	}

}
?>