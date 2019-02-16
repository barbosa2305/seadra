<?php
class ClienteVO {
	private $idcliente = null;
	private $nmcliente = null;
	private $nrcpfcnpj = null;
	private $dsemail = null;
	private $nrtelefone = null;
	private $nrcelular = null;
	private $stativo = null;
	private $idusuariocriacao = null;
	private $dtcriacao = null;
	private $idusuariomodificacao = null;
	private $dtmodificacao = null;
	public function __construct( $idcliente=null, $nmcliente=null, $nrcpfcnpj=null, $dsemail=null, $nrtelefone=null, $nrcelular=null, $stativo=null, $idusuariocriacao=null, $dtcriacao=null, $idusuariomodificacao=null, $dtmodificacao=null ) {
		$this->setIdcliente( $idcliente );
		$this->setNmcliente( $nmcliente );
		$this->setNrcpfcnpj( $nrcpfcnpj );
		$this->setDsemail( $dsemail );
		$this->setNrtelefone( $nrtelefone );
		$this->setNrcelular( $nrcelular );
		$this->setStativo( $stativo );
		$this->setIdusuariocriacao( $idusuariocriacao );
		$this->setDtcriacao( $dtcriacao );
		$this->setIdusuariomodificacao( $idusuariomodificacao );
		$this->setDtmodificacao( $dtmodificacao );
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
	public function setNmcliente( $strNewValue = null )
	{
		$this->nmcliente = $strNewValue;
	}
	public function getNmcliente()
	{
		return $this->nmcliente;
	}
	//--------------------------------------------------------------------------------
	public function setNrcpfcnpj( $strNewValue = null )
	{
		$this->nrcpfcnpj = preg_replace('/[^0-9]/','',$strNewValue);
	}
	public function getNrcpfcnpj()
	{
		return $this->nrcpfcnpj;
	}
	//--------------------------------------------------------------------------------
	public function setDsemail( $strNewValue = null )
	{
		$this->dsemail = $strNewValue;
	}
	public function getDsemail()
	{
		return $this->dsemail;
	}
	//--------------------------------------------------------------------------------
	public function setNrtelefone( $strNewValue = null )
	{
		$this->nrtelefone = $strNewValue;
	}
	public function getNrtelefone()
	{
		return $this->nrtelefone;
	}
	//--------------------------------------------------------------------------------
	public function setNrcelular( $strNewValue = null )
	{
		$this->nrcelular = $strNewValue;
	}
	public function getNrcelular()
	{
		return $this->nrcelular;
	}
	//--------------------------------------------------------------------------------
	public function setStativo( $strNewValue = null )
	{
		$this->stativo = $strNewValue;
	}
	public function getStativo()
	{
		return $this->stativo;
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