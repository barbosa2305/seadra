<?php
/**
 * System generated by SysGen (System Generator with Formdin Framework) 
 * Download SysGen: https://github.com/bjverde/sysgen
 * Download Formdin Framework: https://github.com/bjverde/formDin
 * 
 * SysGen  Version: 0.9.0
 * FormDin Version: 4.2.6-alpha
 * 
 * System ap2v created in: 2018-11-21 23:30:54
 */

class Acesso {

	public function __construct(){
    }    

	//--------------------------------------------------------------------------------	
	public static function login( $login_user, $pwd_user )	{
		$user = UsuarioDAO::selectByLogin($login_user);
        if (password_verify($pwd_user, $user['DSSENHA'][0])) {
            $_SESSION[APLICATIVO]['USER']['IDUSUARIO'] = $user['IDUSUARIO'][0];
            $_SESSION[APLICATIVO]['USER']['LOGIN']  = $login_user;
            $_SESSION[APLICATIVO]['USER']['NOME']  = $user['NMUSUARIO'][0];
            $msg = 1;
        } else {
            $msg = 0;
        }
        return $msg;
    }

    public static function getUserId()	{
        return ArrayHelper::get($_SESSION[APLICATIVO]['USER'],'IDUSUARIO');
    }
    
    public static function getUserLogin() {
        return  ArrayHelper::get($_SESSION[APLICATIVO]['USER'],'LOGIN');
    }

    public static function getUserName() {
        return ArrayHelper::get($_SESSION[APLICATIVO]['USER'],'NOME');
    }

    /**
    * Obtém da sessão todas as informações do usuário: IdUsuario, Login e Nome.
    */
    public static function getUserInfo()	{
	    return ArrayHelper::get($_SESSION[APLICATIVO],'USER');
	}

	public static function isUserAdm(){
        $result = false;     
        if (self::getUserLogin() == 'admin') {
            $result = true;
        }
        return $result;
    }
    public static function changeNewPassword($login_user, $pwd_user_old, $pwd_user_new1, $pwd_user_new2)	{
        if(strlen($pwd_user_new1) < 8){
            throw new DomainException('A senha deve ter no mínimo 8 caracteres.');
        }
        if($pwd_user_new1 != $pwd_user_new2){
            throw new DomainException('As senhas não conferem.');
        }        
		$user = UsuarioDAO::selectByLogin($login_user);
		if (password_verify($pwd_user_old, $user['DSSENHA'][0])) {
            self::changePassword($user['IDUSUARIO'][0],$pwd_user_new1);
		    $msg = 1;
        }else{
            throw new DomainException('A senha atual não está correta.');
        }
        return $msg;
    }
    
    private static function changePassword($idUser, $pwd_user)	{
        $pwd_user_new_hash = password_hash($pwd_user_new1, PASSWORD_DEFAULT);
        $vo = new UsuarioVO();
        $vo->setIdusuario($idUser);
        $vo->setDssenha($pwd_user);
        UsuarioDAO::updateSenha($vo);
    }
}
?>