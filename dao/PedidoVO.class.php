<?php
class PedidoVO {
	private $idpedido = null;
	private $idcliente = null;
	private $dtpedido = null;
	private $vltotal = null;
	private $vldesconto = null;
	private $vlpago = null;
	private $idusuariocriacao = null;
	private $dtcriacao = null;
	private $idusuariomodificacao = null;
	private $dtmodificacao = null;
	public function __construct( $idpedido=null, $idcliente=null, $dtpedido=null, $vltotal=null, $vldesconto=null, $vlpago=null, $idusuariocriacao=null, $dtcriacao=null, $idusuariomodificacao=null, $dtmodificacao=null ) {
		$this->setIdpedido( $idpedido );
		$this->setIdcliente( $idcliente );
		$this->setDtpedido( $dtpedido );
		$this->setVltotal( $vltotal );
		$this->setVldesconto( $vldesconto );
		$this->setVlpago( $vlpago );
		$this->setIdusuariocriacao( $idusuariocriacao );
		$this->setDtcriacao( $dtcriacao );
		$this->setIdusuariomodificacao( $idusuariomodificacao );
		$this->setDtmodificacao( $dtmodificacao );
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
	public function setVltotal( $strNewValue = null )
	{
		$this->vltotal = $strNewValue;
	}
	public function getVltotal()
	{
		return $this->vltotal;
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
	public function setVlpago( $strNewValue = null )
	{
		$this->vlpago = $strNewValue;
	}
	public function getVlpago()
	{
		return $this->vlpago;
	}
	//--------------------------------------------------------------------------------
	public function setIdusuariocriacao( $strNewValue = null )
	{
		$this->idusuariocriacao = $strNewValue;
	}
	public function getIdusuariocriacao()
	{
		return $this->idusuariocriacao;
	}
	//--------------------------------------------------------------------------------
	public function setDtcriacao( $strNewValue = null )
	{
		$this->dtcriacao = $strNewValue;
	}
	public function getDtcriacao()
	{
		return $this->dtcriacao;
	}
	//--------------------------------------------------------------------------------
	public function setIdusuariomodificacao( $strNewValue = null )
	{
		$this->idusuariomodificacao = $strNewValue;
	}
	public function getIdusuariomodificacao()
	{
		return $this->idusuariomodificacao;
	}
	//--------------------------------------------------------------------------------
	public function setDtmodificacao( $strNewValue = null )
	{
		$this->dtmodificacao = $strNewValue;
	}
	public function getDtmodificacao()
	{
		return $this->dtmodificacao;
	}
	//--------------------------------------------------------------------------------
}
?>