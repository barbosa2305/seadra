<?php
class MunicipioVO {
	private $idmunicipio = null;
	private $cdmunicipio = null;
	private $nmmunicipio = null;
	private $idunidadefederativa = null;
	private $stativo = null;
	public function __construct( $idmunicipio=null, $cdmunicipio=null, $nmmunicipio=null, $idunidadefederativa=null, $stativo=null ) {
		$this->setIdmunicipio( $idmunicipio );
		$this->setCdmunicipio( $cdmunicipio );
		$this->setNmmunicipio( $nmmunicipio );
		$this->setIdunidadefederativa( $idunidadefederativa );
		$this->setStativo( $stativo );
	}
	//--------------------------------------------------------------------------------
	public function setIdmunicipio( $strNewValue = null )
	{
		$this->idmunicipio = $strNewValue;
	}
	public function getIdmunicipio()
	{
		return $this->idmunicipio;
	}
	//--------------------------------------------------------------------------------
	public function setCdmunicipio( $strNewValue = null )
	{
		$this->cdmunicipio = $strNewValue;
	}
	public function getCdmunicipio()
	{
		return $this->cdmunicipio;
	}
	//--------------------------------------------------------------------------------
	public function setNmmunicipio( $strNewValue = null )
	{
		$this->nmmunicipio = $strNewValue;
	}
	public function getNmmunicipio()
	{
		return $this->nmmunicipio;
	}
	//--------------------------------------------------------------------------------
	public function setIdunidadefederativa( $strNewValue = null )
	{
		$this->idunidadefederativa = $strNewValue;
	}
	public function getIdunidadefederativa()
	{
		return $this->idunidadefederativa;
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