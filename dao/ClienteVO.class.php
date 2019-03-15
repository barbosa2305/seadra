<?php
class ClienteVO {
	private $idcliente = null;
	private $nmcliente = null;
	private $nrcpfcnpj = null;
	private $dsemail = null;
	private $nrtelefone = null;
	private $nrcelular = null;
	private $dsobservacao = null;
	private $idendereco = null;
	private $dscep = null;
	private $dslogradouro = null;
	private $dscomplemento = null;
	private $dsbairro = null;
	private $dslocalidade = null;
	private $idmunicipio = null;
	private $cdmunicipio = null;
	private $stativo = null;
	private $idusuario = null;
	public function __construct( $idcliente=null,$nmcliente=null,$nrcpfcnpj=null,$dsemail=null,$nrtelefone=null,$nrcelular=null,$dsobservacao=null,$idendereco=null,$dscep=null,$dslogradouro=null,$dscomplemento=null,$dsbairro=null,$dslocalidade=null,$idmunicipio=null,$cdmunicipio=null,$stativo=null,$idusuario=null ){
		$this->setIdcliente( $idcliente );
		$this->setNmcliente( $nmcliente );
		$this->setNrcpfcnpj( $nrcpfcnpj );
		$this->setDsemail( $dsemail );
		$this->setNrtelefone( $nrtelefone );
		$this->setNrcelular( $nrcelular );
		$this->setDsobservacao( $dsobservacao );
		$this->setDscep( $dscep );
		$this->setDslogradouro( $dslogradouro );
		$this->setDscomplemento( $dscomplemento );
		$this->setDsbairro( $dsbairro );
		$this->setDslocalidade( $dslocalidade );
		$this->setIdmunicipio( $idmunicipio );
		$this->setCdmunicipio( $cdmunicipio );
		$this->setStativo( $stativo );
		$this->setIdusuario( $idusuario );
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
	public function setDsobservacao( $strNewValue = null )
	{
		$this->dsobservacao = $strNewValue;
	}
	public function getDsobservacao()
	{
		return $this->dsobservacao;
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
	public function setDscomplemento( $strNewValue = null )
	{
		$this->dscomplemento = $strNewValue;
	}
	public function getDscomplemento()
	{
		return $this->dscomplemento;
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
	public function setCdmunicipio( $strNewValue = null )
	{
		$this->cdmunicipio = $strNewValue;
	}
	public function getCdmunicipio()
	{
		return $this->cdmunicipio;
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
}
?>