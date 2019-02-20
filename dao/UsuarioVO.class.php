<?php
class UsuarioVO {
	private $idusuario = null;
	private $nmusuario = null;
	private $dslogin = null;
	private $dssenha = null;
	private $tpgrupo = null;
    private $stativo = null;
    //--------------------------------------------------------------------------------
	public function __construct( $idusuario=null, $nmusuario=null, $dslogin=null, $dssenha=null, $tpgrupo=null, $stativo=null ) {
		$this->setIdusuario( $idusuario );
		$this->setNmusuario( $nmusuario );
		$this->setDslogin( $dslogin );
		$this->setDssenha( $dssenha );
		$this->setTpgrupo( $tpgrupo );
		$this->setStativo( $stativo );
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
	public function setNmusuario( $strNewValue = null )
	{
		$this->nmusuario = $strNewValue;
	}
	public function getNmusuario()
	{
		return $this->nmusuario;
	}
	//--------------------------------------------------------------------------------
	public function setDslogin( $strNewValue = null )
	{
		$this->dslogin = $strNewValue;
	}
	public function getDslogin()
	{
		return $this->dslogin;
	}
	//--------------------------------------------------------------------------------
	public function setDssenha( $strNewValue = null )
	{
		$this->dssenha = $strNewValue;
	}
	public function getDssenha()
	{
		return $this->dssenha;
	}
	//--------------------------------------------------------------------------------
	public function setTpgrupo( $strNewValue = null )
	{
		$this->tpgrupo = $strNewValue;
	}
	public function getTpgrupo()
	{
		return $this->tpgrupo;
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
}
?>