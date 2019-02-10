<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 1.0.0
 * FormDin Version: 4.2.10-alpha
 * 
 * System seadra created in: 2019-02-10 19:59:11
 */

class Itempedido {


	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		$result = ItempedidoDAO::selectById( $id );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		$result = ItempedidoDAO::selectCount( $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null, $where=null, $page=null,  $rowsPerPage= null){
		$result = ItempedidoDAO::selectAllPagination( $orderBy, $where, $page,  $rowsPerPage );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null, $where=null ){
		$result = ItempedidoDAO::selectAll( $orderBy, $where );
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function save( ItempedidoVO $objVo ){
		$result = null;
		if( $objVo->getIditempedido() ) {
			$result = ItempedidoDAO::update( $objVo );
		} else {
			$result = ItempedidoDAO::insert( $objVo );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		$result = ItempedidoDAO::delete( $id );
		return $result;
	}

}
?>