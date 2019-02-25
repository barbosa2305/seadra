<?php
defined('APLICATIVO') or die();

require_once 'includes/config_conexao.php';

$login = Acesso::getUserLogin();

$frm = new TForm('Sobre', 200, 440);
$frm->setFlat( TRUE );
$frm->setMaximize( TRUE );
$frm->setShowCloseButton( FALSE );

$g = $frm->addGroupField('gpx1');
    $g->setColumns('100,200');
    $frm->addTextField('systemName', 'Sistema:', 45, FALSE, 45, SYSTEM_NAME)->setReadOnly(TRUE);
    $frm->addTextField('user', 'Usuário:', 45, FALSE, 45, $login)->setReadOnly(TRUE);
    $frm->addTextField('versionSystem', 'Versão:', 45, FALSE, 45, SYSTEM_VERSION)->setReadOnly(TRUE);
    $frm->addTextField('versionFormDin', 'Versão base:', 45, FALSE, 45, FORMDIN_VERSION)->setReadOnly(TRUE);
    $pathChangeLog = 'ajuda/changelog.php';
    $changelog = $frm->addTextField('changelog', 'Mais informações:', 20, FALSE, 20, 'Clique para abrir ->');
    $changelog->setReadOnly(TRUE);
    $changelog->setHelpOnLine('Mais informações do sistema', 500, 800, $pathChangeLog);
$g->closeGroup();

$frm->show();
