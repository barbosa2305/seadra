<?php
defined('APLICATIVO') or die();

$width=980;
$height=630;
$frm = new TForm('Exibição do log do sistema', $height, $width);
$arquivoLog = 'error_log';
if ( file_exists($arquivoLog) ){
    //$url = utf8_decode(urldecode($arquivoLog));
    $url = $arquivoLog;
    $frm->addHtmlField('teste', '<iframe style="border:none;" width="'.($width-20).'" height="'.($height-100).'" frameborder="0" marginheight="0" marginwidth="0" src="'.$url.'"></iframe>')->setCss('border', '1px solid blue');
} else {
    $msg = 'Arquivo de log não encontrado.';
    $frm->addHtmlField('teste','<ul><li><h4>'.$msg.'</h4></li></ul>');
}
$frm->show();
