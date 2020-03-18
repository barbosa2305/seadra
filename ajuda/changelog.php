<?php
require_once("../includes/constantes.php");
$msgSysNameVersion = SYSTEM_NAME.' - versão '.SYSTEM_VERSION;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Changelog - <?php echo($msgSysNameVersion); ?></title>
</head>
<body>
    <h2><?php echo($msgSysNameVersion); ?></h2>
    <li>versão 1.4.0</li>
        <ul>
            <li>Criação de relatórios: Produtos, Clientes e Itens Mais Vendidos</li>
            <li>Acréscimo do campo observação do cliente no relatório de pedido de venda.</li>
            <li>Correções de problema de perfomance dos relatórios</li>
        </ul>
    <li>versão 1.3.0</li>
        <ul>
            <li>Cadastro dos horários de verão</li>
            <li>Salvando campos data/hora com timezone correto</li>
        </ul>
    <li>versão 1.2.0</li>
        <ul>
            <li>Acesso ao arquivo de log pelo sistema</li>
        </ul>
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