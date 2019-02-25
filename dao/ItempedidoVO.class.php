<?php
class ItempedidoVO {
	private $iditempedido = null;
	private $idpedido = null;
	private $idproduto = null;
	private $qtitempedido = null;
	private $vltotalitem = null;
	//--------------------------------------------------------------------------------
	public function __construct( $iditempedido=null,$idpedido=null,$idproduto=null,$qtitempedido=null,$vltotalitem=null ){
		$this->setIditempedido( $iditempedido );
		$this->setIdpedido( $idpedido );
		$this->setIdproduto( $idproduto );
		$this->setQtitempedido( $qtitempedido );
		$this->setVltotalitem( $vltotalitem );
	}
	//--------------------------------------------------------------------------------
	public function setIditempedido( $strNewValue = null )
	{
		$this->iditempedido = $strNewValue;
	}
	public function getIditempedido()
	{
		return $this->iditempedido;
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
	public function setIdproduto( $strNewValue = null )
	{
		$this->idproduto = $strNewValue;
	}
	public function getIdproduto()
	{
		return $this->idproduto;
	}
	//--------------------------------------------------------------------------------
	public function setQtitempedido( $strNewValue = null )
	{
		$this->qtitempedido = $strNewValue;
	}
	public function getQtitempedido()
	{
		return $this->qtitempedido;
	}
	//--------------------------------------------------------------------------------
	public function setVltotalitem( $strNewValue = null )
	{
		$this->vltotalitem = $strNewValue;
	}
	public function getVltotalitem()
	{
		return $this->vltotalitem;
	}
	//--------------------------------------------------------------------------------
}
?>