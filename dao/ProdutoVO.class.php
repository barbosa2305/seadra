<?php
class ProdutoVO {
	private $idproduto = null;
	private $nmproduto = null;
	private $vlprecocusto = null;
	private $vlprecovenda = null;
	private $stativo = null;
	private $idusuario = null;
	public function __construct( $idproduto=null, $nmproduto=null, $vlprecocusto=null, $vlprecovenda=null, $stativo=null, $idusuario=null ) {
		$this->setIdproduto( $idproduto );
		$this->setNmproduto( $nmproduto );
		$this->setVlprecocusto( $vlprecocusto );
		$this->setVlprecovenda( $vlprecovenda );
		$this->setStativo( $stativo );
		$this->setIdusuario( $idusuario );
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