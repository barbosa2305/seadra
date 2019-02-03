<?php
defined('APLICATIVO') or die();

require_once 'includes/config_conexao.php';

$login = Acesso::getUserLogin();

$frm = new TForm('Sobre', 200, 440);
$frm->setFlat(true);
$frm->setMaximize(true);

$g = $frm->addGroupField('gpx1');
    $g->setColumns('100,160');

    $frm->addTextField('systemName', 'Sistema:', 50, false, 50, SYSTEM_NAME)->setReadOnly(true);
    $frm->addTextField('user', 'Usuário:', 50, false, 50, $login)->setReadOnly(true);
    $frm->addTextField('versionSystem', 'Versão:', 50, false, 50, SYSTEM_VERSION)->setReadOnly(true);
    $frm->addTextField('versionFormDin', 'Versão base:', 50, false, 50, FORMDIN_VERSION)->setReadOnly(true);

    $pathChangeLog = 'ajuda/changelog.php';
    $changelog = $frm->addTextField('changelog', 'Mais informações:', 20, false, 20, 'Clique para abrir ->');
    $changelog->setReadOnly(true);
    $changelog->setHelpOnLine('Mais informações do sistema', 500, 800, $pathChangeLog);
$g->closeGroup();

$frm->show();
