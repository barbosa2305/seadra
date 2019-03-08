<?php
class ItempedidoVO {
	private $iditempedido = null;
	private $idpedido = null;
	private $idproduto = null;
	private $qtitempedido = null;
	private $vldesconto = null;
	private $idusuario = null; // usuario que será gravado no pedido
	//--------------------------------------------------------------------------------
	public function __construct( $iditempedido=null,$idpedido=null,$idproduto=null,$qtitempedido=null,$vldesconto=null,$idusuario=null ){
		$this->setIditempedido( $iditempedido );
		$this->setIdpedido( $idpedido );
		$this->setIdproduto( $idproduto );
		$this->setQtitempedido( $qtitempedido );
		$this->setVldesconto( $vldesconto );
		$this->setIdusuario( $idusuario );
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
	public function setVldesconto( $strNewValue = null )
	{
		$this->vldesconto = $strNewValue;
	}
	public function getVldesconto()
	{
		return $this->vldesconto;
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