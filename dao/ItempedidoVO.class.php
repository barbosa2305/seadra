<?php
class ItempedidoVO {
	private $iditempedido = null;
	private $idpedido = null;
	private $idpedido = null;
	private $idproduto = null;
	private $idproduto = null;
	private $qtitempedido = null;
	public function __construct( $iditempedido=null, $idpedido=null, $idpedido=null, $idproduto=null, $idproduto=null, $qtitempedido=null ) {
		$this->setIditempedido( $iditempedido );
		$this->setIdpedido( $idpedido );
		$this->setIdpedido( $idpedido );
		$this->setIdproduto( $idproduto );
		$this->setIdproduto( $idproduto );
		$this->setQtitempedido( $qtitempedido );
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
}
?>