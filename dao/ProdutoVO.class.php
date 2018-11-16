<?php
class ProdutoVO {
	private $idproduto = null;
	private $nmproduto = null;
	private $vlprecocusto = null;
	private $vlprecovenda = null;
	private $stativo = null;
	private $idusuariocriacao = null;
	private $dtcriacao = null;
	private $idusuariomodificacao = null;
	private $dtmodificacao = null;
	public function __construct( $idproduto=null, $nmproduto=null, $vlprecocusto=null, $vlprecovenda=null, $stativo=null, $idusuariocriacao=null, $dtcriacao=null, $idusuariomodificacao=null, $dtmodificacao=null ) {
		$this->setIdproduto( $idproduto );
		$this->setNmproduto( $nmproduto );
		$this->setVlprecocusto( $vlprecocusto );
		$this->setVlprecovenda( $vlprecovenda );
		$this->setStativo( $stativo );
		$this->setIdusuariocriacao( $idusuariocriacao );
		$this->setDtcriacao( $dtcriacao );
		$this->setIdusuariomodificacao( $idusuariomodificacao );
		$this->setDtmodificacao( $dtmodificacao );
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
	public function setNmproduto( $strNewValue = null )
	{
		$this->nmproduto = $strNewValue;
	}
	public function getNmproduto()
	{
		return $this->nmproduto;
	}
	//--------------------------------------------------------------------------------
	public function setVlprecocusto( $strNewValue = null )
	{
		$this->vlprecocusto = $strNewValue;
	}
	public function getVlprecocusto()
	{
		return $this->vlprecocusto;
	}
	//--------------------------------------------------------------------------------
	public function setVlprecovenda( $strNewValue = null )
	{
		$this->vlprecovenda = $strNewValue;
	}
	public function getVlprecovenda()
	{
		return $this->vlprecovenda;
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