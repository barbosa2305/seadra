<?php
/**
 * Classe de constantes contendo as mensagens do sistema. 
 */
final class Mensagem {
	 
    const INFORME_ADMIN   = 'Entre em contato com o administrador do sistema para relatar o problema.';    
    const ERRO_INTERNO = 'Ocorreu um erro interno. ' . self::INFORME_ADMIN;
    const OPERACAO_FALHOU = 'Operação não realizada.';

    const REGISTRO_GRAVADO = 'Registro gravado com sucesso!';
    
    const SENHA_TAMANHO_MINIMO = 'A senha deve ter no mínimo 8 caracteres.';
    const SENHAS_NAO_COINCIDEM = 'As senhas não conferem.';
    const SENHA_ATUAL_INCORRETA = 'A senha atual não está correta.';    
}