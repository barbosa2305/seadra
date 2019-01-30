<?php

if (!defined('DS')) define('DS', DIRECTORY_SEPARATOR); 

class ServidorConfig {
    /**
     * Contém uma instância de ServidorConfig para implementação do padrão GoF Singleton.
     */
    private static $instancia = null;
    
    private $perfilAdm = array();
    
    /**
     * Contém o nome do driver PDO para conexão com o SQL Server de acordo com o sistema operacional.
     * @var string
     */
    private $driverPDO = '';
    
    private $config = array();
    
    /**
     * Construtor Singleton.
     */
    private function __construct() {
        $root     = $_SERVER['DOCUMENT_ROOT'];
        $nome_ini = 'seadra.ini';
        $ini_path = $root .DS. 'config' . DS . $nome_ini;
        $ini_conf = null;
        if(file_exists($ini_path)){
            $ini_conf = parse_ini_file($ini_path, true);
        }else{ 
            throw new InvalidArgumentException('Arquivo '.$nome_ini.' não encontrado.');
        }
        
        foreach($ini_conf as &$conf) {
            if(!empty($conf['database'])) {
                $conf['database'] = strtoupper($conf['database']);
            }
        }
        
        $this->perfilAdm         = $ini_conf['ds-adm'];
        $this->config 			 = $ini_conf['config'];
        //$this->driverPDO         = $this->config['drive'];
    }
    
    /**
     * Trata a clonagem da classe como impossível de ser clonada. Ao se utilizar o operador clone sobre uma instância da classe o sistema é encerrado.
     */
    public function __clone() {
        die('O objeto do tipo ServidorConfig não pode ser clonado.');
    }
    
    /**
     * Retorna a única instância da classe que o sistema deverá acessar. Implementação do padrão GoF Singleton.
     * @return ServidorConfig
     */
    public static function getInstancia() {
        if(is_null(self::$instancia)) {
            self::$instancia = new self;
        }
        return self::$instancia;
    }
    
    /**
     * Retorna o nome do driver PDO para conexão com o SQL Server de acordo com o sistema operacional.
     * @var string
     */
    public function getDriverPDO() {
        return $this->driverPDO;
    }
    
    /**
     * Retorna um array associativo contendo dados de configuração para acesso ao banco de dados. As chaves desse array são:
     * <ul>
     * <li>hostname</li>
     * <li>database</li>
     * <li>username</li>
     * <li>password</li>
     * </ul>
     * @var array
     */
    public function getPerfilAdm() {
        return $this->perfilAdm;
    }
    
    public function getConfig(){
        return $this->config;
    }
    
    public function getConfigParam($param){
        return $this->config[$param];
    }
    
}