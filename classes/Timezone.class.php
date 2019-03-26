<?php

class Timezone {

    const TIMEZONE = '-03:00';
    const TIMEZONE_VERAO = '-02:00';

	public function __construct(){
	}
	//--------------------------------------------------------------------------------
    public static function set(){
        $_SESSION[APLICATIVO]['TIMEZONE'] = self::TIMEZONE;
        $horarioVerao = HorarioveraoDAO::selectUltimoPeriodo('DTINICIO DESC');
        if ( !empty($horarioVerao) ){
           $hoje = DateTimeHelper::getNow();
            if ( ($hoje >= $horarioVerao['DTINICIO'][0]) && ($hoje <= $horarioVerao['DTFIM'][0]) ){
                $_SESSION[APLICATIVO]['TIMEZONE'] = self::TIMEZONE_VERAO;
            }
        }
    }
    //--------------------------------------------------------------------------------
    public static function get(){
        return ArrayHelper::get( $_SESSION[APLICATIVO],'TIMEZONE' );
    }
	//--------------------------------------------------------------------------------
}
?>