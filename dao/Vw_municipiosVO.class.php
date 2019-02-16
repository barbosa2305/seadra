<?php
class Vw_municipiosVO {
	private $idunidadefederativa = null;
	private $dssigla = null;
	private $cdmunicipio = null;
	private $nmmunicipio = null;
	public function __construct( $idunidadefederativa=null, $dssigla=null, $cdmunicipio=null, $nmmunicipio=null ) {
		$this->setIdunidadefederativa( $idunidadefederativa );
		$this->setDssigla( $dssigla );
		$this->setCdmunicipio( $cdmunicipio );
		$this->setNmmunicipio( $nmmunicipio );
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
	public function setDssigla( $strNewValue = null )
	{
		$this->dssigla = $strNewValue;
	}
	public function getDssigla()
	{
		return $this->dssigla;
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
}
?>