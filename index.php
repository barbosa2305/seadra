<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 0.9.0
 * FormDin Version: 4.2.7-alpha
 * 
 * System seadra created in: 2018-12-03 21:17:31
 */

/* Configuracoes 
* config_constantes_dev.php   => DESENVOLVIMENTO
* config_constantes.php       => PRODUCAO
*/
require_once('../config/config_constantes_dev.php');
//require_once('../config/config_constantes.php');

require_once('includes/constantes.php');
require_once('includes/config_conexao.php');

//FormDin version: 4.2.7-alpha
require_once('../base/classes/webform/TApplication.class.php');
require_once('classes/autoload_seadra.php');


$app = new TApplication(); // criar uma instancia do objeto aplicacao
$app->setAppRootDir(__DIR__);
$app->setTitle('');
$app->setTitle(SYSTEM_NAME);
$app->setSubtitle(SYSTEM_NAME_SUB);
$app->setSigla(ucwords(strtolower(SYSTEM_SIGLA))); 
$app->setVersionSystem(SYSTEM_VERSION);
$app->addCssFile(CSS_FILE);

//Parametros para login
$app->setLoginFile('includes/tela_login.php');
$app->setLoginInfo(Acesso::getUserName());
$app->setBackgroundImage('../css/imagens/app/bg_listrado.jpg');
$app->setMenuTheme(TEMA_MENU);

$app->setMainMenuFile('includes/menu.php');
$app->setDefaultModule('pedido.php');

$app->run();
?>