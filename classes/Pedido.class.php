<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 1.0.0
 * FormDin Version: 4.2.12-alpha
 * 
 * System seadra created in: 2019-02-15 23:59:32
 */

class Pedido {

	public function __construct(){
	}
	//--------------------------------------------------------------------------------
	public static function selectById( $id ){
		return PedidoDAO::selectById( $id );
	}
	//--------------------------------------------------------------------------------
	public static function selectCount( $where=null ){
		return PedidoDAO::selectCount( $where );
	}
	//--------------------------------------------------------------------------------
	public static function selectAllPagination( $orderBy=null,$where=null,$page=null,$rowsPerPage=null ){
		return PedidoDAO::selectAllPagination( $orderBy,$where,$page,$rowsPerPage );
	}
	//--------------------------------------------------------------------------------
	public static function selectAll( $orderBy=null,$where=null ){
		return PedidoDAO::selectAll( $orderBy,$where );
	}
	//--------------------------------------------------------------------------------
	public static function save( PedidoVO $objVo ){
		$result = null;
		if ( $objVo->getIdpedido() ) {
			$result = PedidoDAO::update( $objVo );
		} else {
			self::validar( $objVo );
			$result = PedidoDAO::insert( $objVo );
		}
		return $result;
	}
	//--------------------------------------------------------------------------------
	public static function delete( $id ){
		return PedidoDAO::delete( $id );
	}
	//--------------------------------------------------------------------------------
    private static function validar( PedidoVO $objVo ){
        self::validarPedidoCadastrado( $objVo );
	}
	//--------------------------------------------------------------------------------
    private static function validarPedidoCadastrado( PedidoVO $objVo ){
        $where = array( 'IDCLIENTE'=>$objVo->getIdcliente(),'DTPEDIDO'=>$objVo->getDtpedido() );
        $dados = self::selectAll( null,$where );
        if( !empty($dados) ){
            throw new DomainException( Mensagem::PEDIDO_JA_CADASTRADO ); 
        }
    }
	//--------------------------------------------------------------------------------
}
?>