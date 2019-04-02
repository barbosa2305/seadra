<?php

class RelClientes extends TPDF {
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
        
        foreach( $dados['IDCLIENTE'] as  $key => $idCliente ){ 
            $dados['NRCPFCNPJ'][$key] = utf8_decode(trim($this->applyMaskCpfCnpj($dados['NRCPFCNPJ'][$key])));
            $dados['NRTELEFONE'][$key] = utf8_decode(trim($this->applyMaskTelefone($dados['NRTELEFONE'][$key])));
            $dados['NRCELULAR'][$key] = utf8_decode(trim($this->applyMaskCelular($dados['NRCELULAR'][$key])));
        } 
        
        $this->clearColumns();
        $this->setData( $dados );
        $this->addColumn(utf8_decode('Código'),12,'C','IDCLIENTE',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Nome'),46,'L','NMCLIENTE',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('CPF/CNPJ'),26,'C','NRCPFCNPJ',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('E-mail'),50,'C','DSEMAIL',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Telefone'),20,'C','NRTELEFONE',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Celular'),24,'C','NRCELULAR',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Ativo ?'),12,'C','STATIVO',null,null,$tamanho,$fontCor,$tipo);
        $this->printRows();
        
        
        /*
        $header = array('Código','Nome','CPF/CNPJ','E-mail','Telefone','Celular','Ativo ?');
        $w = array( 12,55,24,48,18,20,12 ); // Largura das colunas

        for ($i=0;$i<count($header);$i++) // Cabeçalho da tabela
            $this->Cell($w[$i],$h,utf8_decode($header[$i]),'T,B',0,'C');
        $this->Ln();

        $this->SetFont('Arial','',$tamanho);
        foreach( $dados['IDCLIENTE'] as  $key => $idCliente ){ // Dados
            $this->Cell($w[0],$h,utf8_decode(trim($dados['IDCLIENTE'][$key])),0,0,'C');
            $this->Cell($w[1],$h,utf8_decode(trim($dados['NMCLIENTE'][$key])),0,0,'L');
            $this->Cell($w[2],$h,utf8_decode(trim($this->applyMaskCpfCnpj($dados['NRCPFCNPJ'][$key]))),0,0,'C');
            $this->Cell($w[3],$h,utf8_decode(trim($dados['DSEMAIL'][$key])),0,0,'C');
            $this->Cell($w[4],$h,utf8_decode(trim($this->applyMaskTelefone($dados['NRTELEFONE'][$key]))),0,0,'C');
            $this->Cell($w[5],$h,utf8_decode(trim($this->applyMaskCelular($dados['NRCELULAR'][$key]))),0,0,'C');
            $this->Cell($w[5],$h,utf8_decode(trim($dados['STATIVO'][$key])),0,1,'C');
        } 
        */

    }
    private function applyMaskCpfCnpj( $campo ){
        if (strlen($campo) == 11) {
            $result = mask($campo,'###.###.###-##');
        } else {
            $result = mask($campo,'##.###.###/####-##');
        }
        return $result;
    }
    private function applyMaskTelefone( $campo ){
        if (strlen($campo) == 8) {
            $result = mask($campo,'####-####');
        } elseif (strlen($campo) == 9) { 
            $result = mask($campo,'(##) #####-####');  
        } elseif (strlen($campo) > 9) { 
            $result = mask($campo,'(##) ####-####');  
        }
        return $result;
    }
    private function applyMaskCelular( $campo ){
        if (strlen($campo) == 9) {
            $result = mask($campo,'#####-####');
        } elseif (strlen($campo) > 9) { 
            $result = mask($campo,'(##) #####-####');  
        }
        return $result;
    }
}

// Inicio do relatorio
$orderBy = 'NMCLIENTE';
$dados = Cliente::selectAll( $orderBy );
if ( empty($dados) ){
    echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
} else {
    $pdf = new RelClientes('P');
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
    $pdf->linha(0,$h,'Relatório de Clientes',0,1,'C');
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
    $pdf->SetY(-16);
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->linha(0,$h,'Página: '.$pdf->PageNo().'/{nb}','T',1,'C');
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
