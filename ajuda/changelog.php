<?php
require_once("../includes/constantes.php");
$msgSysNameVersion = SYSTEM_NAME.' - v'.SYSTEM_VERSION;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Changelog - <?php echo($msgSysNameVersion); ?></title>
</head>
<body>
    <h2><?php echo($msgSysNameVersion); ?></h2>
    <li>versão 1.1.0</li>
        <ul>
            <li>Campo quantidade do item alterado para decimal, com duas casas decimais</li>
            <li>Correção de erro na busca de itens por quantidade e valor do desconto</li>
        </ul>
    <li>versão 1.0.0</li>
        <ul>
            <li>Primeira versão</li>
        </ul>
</body>
</html>