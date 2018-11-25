<?php
class UnidadefederativaVO {
	private $idunidadefederativa = null;
	private $dssigla = null;
	private $dsnome = null;
	public function __construct( $idunidadefederativa=null, $dssigla=null, $dsnome=null ) {
		$this->setIdunidadefederativa( $idunidadefederativa );
		$this->setDssigla( $dssigla );
		$this->setDsnome( $dsnome );
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
	public function setDsnome( $strNewValue = null )
	{
		$this->dsnome = $strNewValue;
	}
	public function getDsnome()
	{
		return $this->dsnome;
	}
	//--------------------------------------------------------------------------------
}
?>