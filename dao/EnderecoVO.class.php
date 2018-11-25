<?php
class EnderecoVO {
	private $idendereco = null;
	private $dscep = null;
	private $dslogradouro = null;
	private $dsbairro = null;
	private $dslocalidade = null;
	private $idmunicipio = null;
	public function __construct( $idendereco=null, $dscep=null, $dslogradouro=null, $dsbairro=null, $dslocalidade=null, $idmunicipio=null ) {
		$this->setIdendereco( $idendereco );
		$this->setDscep( $dscep );
		$this->setDslogradouro( $dslogradouro );
		$this->setDsbairro( $dsbairro );
		$this->setDslocalidade( $dslocalidade );
		$this->setIdmunicipio( $idmunicipio );
	}
	//--------------------------------------------------------------------------------
	public function setIdendereco( $strNewValue = null )
	{
		$this->idendereco = $strNewValue;
	}
	public function getIdendereco()
	{
		return $this->idendereco;
	}
	//--------------------------------------------------------------------------------
	public function setDscep( $strNewValue = null )
	{
		$this->dscep = $strNewValue;
	}
	public function getDscep()
	{
		return $this->dscep;
	}
	//--------------------------------------------------------------------------------
	public function setDslogradouro( $strNewValue = null )
	{
		$this->dslogradouro = $strNewValue;
	}
	public function getDslogradouro()
	{
		return $this->dslogradouro;
	}
	//--------------------------------------------------------------------------------
	public function setDsbairro( $strNewValue = null )
	{
		$this->dsbairro = $strNewValue;
	}
	public function getDsbairro()
	{
		return $this->dsbairro;
	}
	//--------------------------------------------------------------------------------
	public function setDslocalidade( $strNewValue = null )
	{
		$this->dslocalidade = $strNewValue;
	}
	public function getDslocalidade()
	{
		return $this->dslocalidade;
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
}
?>