<?php
class HorarioveraoVO {
	private $idhorarioverao = null;
	private $dtinicio = null;
	private $dtfim = null;
	public function __construct( $idhorarioverao=null, $dtinicio=null, $dtfim=null ) {
		$this->setIdhorarioverao( $idhorarioverao );
		$this->setDtinicio( $dtinicio );
		$this->setDtfim( $dtfim );
	}
	//--------------------------------------------------------------------------------
	public function setIdhorarioverao( $strNewValue = null )
	{
		$this->idhorarioverao = $strNewValue;
	}
	public function getIdhorarioverao()
	{
		return $this->idhorarioverao;
	}
	//--------------------------------------------------------------------------------
	public function setDtinicio( $strNewValue = null )
	{
		$this->dtinicio = $strNewValue;
	}
	public function getDtinicio()
	{
		return $this->dtinicio;
	}
	//--------------------------------------------------------------------------------
	public function setDtfim( $strNewValue = null )
	{
		$this->dtfim = $strNewValue;
	}
	public function getDtfim()
	{
		return $this->dtfim;
	}
	//--------------------------------------------------------------------------------
}
?>