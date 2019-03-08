<?php
class PedidoVO {
	private $idpedido = null;
	private $idcliente = null;
	private $dtpedido = null;
	private $idusuario = null;
	public function __construct( $idpedido=null,$idcliente=null,$dtpedido=null,$idusuario=null ){
		$this->setIdpedido( $idpedido );
		$this->setIdcliente( $idcliente );
		$this->setDtpedido( $dtpedido );
		$this->setIdusuario( $idusuario );
	}
	//--------------------------------------------------------------------------------
	public function setIdpedido( $strNewValue = null )
	{
		$this->idpedido = $strNewValue;
	}
	public function getIdpedido()
	{
		return $this->idpedido;
	}
	//--------------------------------------------------------------------------------
	public function setIdcliente( $strNewValue = null )
	{
		$this->idcliente = $strNewValue;
	}
	public function getIdcliente()
	{
		return $this->idcliente;
	}
	//--------------------------------------------------------------------------------
	public function setDtpedido( $strNewValue = null )
	{
		$this->dtpedido = $strNewValue;
	}
	public function getDtpedido()
	{
		return $this->dtpedido;
	}
	//--------------------------------------------------------------------------------
	public function setIdusuario( $strNewValue = null )
	{
		$this->idusuario = $strNewValue;
	}
	public function getIdusuario()
	{
		return $this->idusuario;
	}
	//--------------------------------------------------------------------------------
}
?>