<?php
class ConfiguracaoVO {
	private $idconfiguracao = null;
	private $dsemitente = null;
	private $dsenderecoemitente = null;
	private $dstelefoneemitente = null;
	//--------------------------------------------------------------------------------
	public function __construct( $idconfiguracao=null,$dsemitente=null,$dsenderecoemitente=null,$dstelefoneemitente=null ){
		$this->setIdconfiguracao( $idconfiguracao );
		$this->setDsemitente( $dsemitente );
		$this->setDsenderecoemitente( $dsenderecoemitente );
		$this->setDstelefoneemitente( $dstelefoneemitente );
	}
	//--------------------------------------------------------------------------------
	public function setIdconfiguracao( $strNewValue = null )
	{
		$this->idconfiguracao = $strNewValue;
	}
	public function getIdconfiguracao()
	{
		return $this->idconfiguracao;
	}
	//--------------------------------------------------------------------------------
	public function setDsemitente( $strNewValue = null )
	{
		$this->dsemitente = $strNewValue;
	}
	public function getDsemitente()
	{
		return $this->dsemitente;
	}
	//--------------------------------------------------------------------------------
	public function setDsenderecoemitente( $strNewValue = null )
	{
		$this->dsenderecoemitente = $strNewValue;
	}
	public function getDsenderecoemitente()
	{
		return $this->dsenderecoemitente;
	}
	//--------------------------------------------------------------------------------
	public function setDstelefoneemitente( $strNewValue = null )
	{
		$this->dstelefoneemitente = $strNewValue;
	}
	public function getDstelefoneemitente()
	{
		return $this->dstelefoneemitente;
	}
	//--------------------------------------------------------------------------------
}
?>