<?php
/**
 * Classe de constantes contendo as mensagens do sistema. 
 */
final class Mensagem {
	 
    const INFORME_ADMIN   = 'Entre em contato com o administrador do sistema para relatar o problema.';    
    const ERRO_INTERNO = 'Ocorreu um erro interno. ' . self::INFORME_ADMIN;
    const OPERACAO_FALHOU = 'Operação não realizada.';
    const OPERACAO_NAO_PERMITIDA = 'Operação não permitida.';
    const OPERACAO_COM_SUCESSO = 'Operação realizada com sucesso.';
    
    const USUARIO_SENHA_INCORRETOS = 'Usuário e/ou senha incorretos.';
    const USUARIO_JA_CADASTRADO = 'O usuário já está cadastrado.';
    const SENHA_TAMANHO_MINIMO = 'A senha deve ter no mínimo 8 caracteres.';
    const SENHAS_NAO_COINCIDEM = 'As senhas não conferem.';
    const SENHA_ATUAL_INCORRETA = 'A senha atual não está correta.';    
    const SENHA_PADRAO_USUARIO = 'A senha padrão: 12345678, foi definida para este usuário.';

    const CPFCNPJ_JA_CADASTRADO = 'O CPF/CNPJ informado já está cadastrado.';
}