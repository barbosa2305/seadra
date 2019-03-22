<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 0.9.0
 * FormDin Version: 4.2.7-alpha
 * 
 * System seadra created in: 2018-12-03 21:17:56
 */

$menu = new TMenuDhtmlx();

$menu->add('1', null, 'Principal', null, null, 'menu-alt-512.png');
$menu->add('1.0','1','Cliente','modulos/cliente.php');
$menu->add('1.1','1','Produto','modulos/produto.php');
$menu->add('1.2','1','Pedido','modulos/pedido.php');

$menu->add('2', null, 'Acesso', null, null, 'icon-key-yellow.png');
$menu->add('2.1','2','Alterar minha senha','modulos/alterasenha.php');

if ( Acesso::getUserGroup() == Acesso::USER_GRUPO_ADMIN ) {
    $menu->add('2.2','2','Usuário','modulos/usuario.php');
}

$menu->add('9', null, 'Sobre', 'modulos/sys_about.php', null, 'information16.gif');

if (Acesso::isUserLoggedAdm()) {
    $menu->add('10',null,'Adm',null,null,'settings-gear-tool-03.png'); 
    $menu->add('10.1','10','Configuração','modulos/configuracao.php');
    $menu->add('10.2','10','Importar dados','modulos/importadados.php');
    $menu->add('10.3','10','Ambiente Resumido','modulos/sys_environment_summary.php');
    $menu->add('10.4','10','PHPInfo','modulos/sys_environment.php');
    $menu->add('10.5','10','Exibir o log','modulos/exibelog.php');
}

$menu->getXml();
?>