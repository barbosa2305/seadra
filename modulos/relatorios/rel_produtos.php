<?php

class RelProdutos extends TPDF {
    private $dados;
    private $gridFontTipo = 'Arial';
    private $gridFontTamanho = 7;
    private $gridFontCor = 'Black';
    
    public function getDados(){
        return $this->dados;
    }
    public function setDados( $dados ){
        $this->dados = $dados;
    }
    public function linha( $w,$h=0,$txt='',$border=0,$ln=0,$align='L',$fill=false ){
        $txt = utf8_decode(trim($txt));
        $this->cell($w,$h,$txt,$border,$ln,$align,$fill);
    }
    public function convertArrayUtf8ParaIso( $arrayUtf8 ){
        $arrayIso = null;
        if ( !is_array($arrayUtf8) ){
            $arrayIso = $arrayUtf8;
        } else {
            foreach ( $arrayUtf8 as $key => $arr ){
                foreach ( $arr as $index => $value ){
                    $arrayIso[$key][$index] = utf8_decode(trim($value));
                }
            }
        }
        return $arrayIso;
    }
    public function Detalhes(){
        $dados =$this->getDados();
        $dados = $this->convertArrayUtf8ParaIso($dados);
        $tipo = $this->gridFontTipo;
        $tamanho = $this->gridFontTamanho;
        $fontCor = $this->gridFontCor;
        $h = 6;

        $this->clearColumns();
        $this->setData( $dados );
        $this->addColumn(utf8_decode('Código'),12,'C','IDPRODUTO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Descrição'),126,'L','NMPRODUTO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Unid.'),12,'C','DSUNIDADEMEDIDA',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Vl. Custo (R$)'),20,'R','VLPRECOCUSTO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Vl. Venda (R$)'),20,'R','VLPRECOVENDA',null,null,$tamanho,$fontCor,$tipo);
        $this->printRows();
    
    }
}

// Inicio do relatorio
$orderBy = 'NMPRODUTO';
$dados = Produto::selectAll( $orderBy );
if ( empty($dados) ){
    echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
} else {
    $pdf = new RelProdutos('P');
    $pdf->setDados($dados);
    $pdf->AddPage();
    $pdf->Detalhes(); 
    $pdf->show();
}

// função chamada automaticamente pela classe TPDF quando existir
function cabecalho(TPDF $pdf)
{
    $fontSize = 10;
    $h = 6;
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',$fontSize+4);
    $pdf->linha(0,$h,'Relatório de Produtos',0,1,'C');
    $pdf->Ln(5);
    
    $pdf->SetFont('Arial','',$fontSize);
    $config = Configuracao::selectAll();
    $empresa = trim($config['DSEMITENTE'][0]);
    $pdf->linha(0, $h, $empresa, 0, 1);
    $pdf->SetXY(10,26);
    $pdf->SetFont('Arial','',7);
    $pdf->linha(0,$h,'Emitido em: '.date('d/m/Y H:i'),0,1,'R');
    $pdf->Ln(2);
}

// função chamada automaticamente pela classe TPDF quando existir
function rodape(TPDF $pdf)
{   
    $fontSize = 8;
    $h = 6;
    $pdf->SetY(-14);
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->linha(0,$h,'Página: '.$pdf->PageNo().'/{nb}',0,1,'C');
}

function mask($val, $mask)
{
    $maskared = '';
    $k = 0;
    for ($i = 0; $i <= strlen($mask) - 1; $i++){
        if ($mask[$i] == '#'){
            if (isset($val[$k]))
            $maskared .= $val[$k++];
        } else {
            if (isset($mask[$i]))
            $maskared .= $mask[$i];
        }
    }
    return $maskared;
}
