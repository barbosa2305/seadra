<?php

class RelPedido extends TPDF {
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
    public function Itens(){
        $dados =$this->getDados();
        $dados = $this->convertArrayUtf8ParaIso($dados);
        $tipo = $this->gridFontTipo;
        $tamanho = $this->gridFontTamanho;
        $fontCor = $this->gridFontCor;

        $this->clearColumns();
        $this->setData( $dados );
        $this->addColumn(utf8_decode('Item'),9,'C','NRITEM',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Código'),12,'C','IDPRODUTOFORMATADO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Descrição'),83,'L','NMPRODUTO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Unid.'),10,'C','DSUNIDADEMEDIDA',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Qtd'),12,'C','QTITEMPEDIDO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Valor Unit. (R$)'),23,'R','VLPRECOVENDA',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Desc. (R$)'),18,'R','VLDESCONTO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Total (R$)'),23,'R','VLTOTALITEMCOMDESCONTO',null,null,$tamanho,$fontCor,$tipo);
        $this->printRows();
    }
    public function Totais(){
        $dados =$this->getDados();
        $h = 6;
        $w1 = 132;
        $w2 = 0;
        $fontSize = 8;
        $this->SetFont('Arial','',$fontSize);
        $this->linha($w1,$h,'Total dos produtos (R$): ',1,0,'R');
        $this->linha($w2,$h,$dados['VLPEDIDO'][0],1,1,'R');
        $this->linha($w1,$h,'Descontos (R$): ',1,0,'R');
        $this->linha($w2,$h,$dados['VLTOTALDESCONTO'][0],1,1,'R');
        $this->SetFont('Arial','B',$fontSize);
        $this->linha($w1,$h,'Total (R$): ',1,0,'R');
        $this->linha($w2,$h,$dados['VLTOTAL'][0],1,1,'R');
        $this->Ln();
    }
    public function Assinaturas(){
        $fontSize = 8;
        $w = 80;
        $h = 6;
        $this->SetFont('Arial','',$fontSize);
        $y = $this->GetY()+15;
        $this->SetXY(16,$y);
        $this->linha($w,$h,'Assinatura do vendedor','T',1,'C');  
        $this->SetXY(114,$y);
        $this->linha($w,$h,'Assinatura do cliente','T',1,'C');
        $this->Ln();
    }
}

if ( !ArrayHelper::validateUndefined($_REQUEST,'IDPEDIDO') ){
    echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
} else {
    $idPedido = $_REQUEST['IDPEDIDO'];
    $where = array( 'IDPEDIDO'=>$idPedido );
    $orderBy = 'NMPRODUTO';
    $dados = Itempedido::selectAll( $orderBy,$where );
    if ( empty($dados) ){
        echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
    } else {
        $pdf = new RelPedido('P');
        $pdf->setDados($dados);
        $pdf->AddPage();
        $pdf->Itens(); 
        $pdf->Totais();
        $pdf->Assinaturas();
        $pdf->show();
    }
}

// função chamada automaticamente pela classe TPDF quando existir
function cabecalho(TPDF $pdf)
{
    $dados = $pdf->getDados();
    $fontSize = 8;
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',$fontSize+1);
    $h = 6;
    $pdf->linha(0, $h, 'PEDIDO DE VENDA', 1, 1, 'C');
    $textoFixo = "NÃO É DOCUMENTO FISCAL - NÃO É VÁLIDO COMO RECIBO E COMO GARANTIA DE MERCADORIA - NÃO COMPROVA PAGAMENTO";
    $pdf->SetFont('Arial',null,$fontSize-2);
    $pdf->MultiCell(150, $h, utf8_decode($textoFixo), 1, 'C');
    $pdf->SetXY(160,21);
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->linha(0, $h, 'Página: '.$pdf->PageNo().'/{nb}', 1, 1, 'C');

    $pdf->SetFont('Arial','',$fontSize);
    $config = Configuracao::selectAll();
    $empresa = trim($config['DSEMITENTE'][0]);
    $emitente = 'Emitente: '.$empresa;
    $pdf->linha(0, $h, $emitente, 1, 1);

    $enderecoEmitente = trim($config['DSENDERECOEMITENTE'][0]);
    $telefoneEmitente = trim($config['DSTELEFONEEMITENTE'][0]);
    $textoLogotipo = "\n".$empresa."\n\n".$enderecoEmitente."\nTelefone: ".$telefoneEmitente."\n\n";
    $pdf->MultiCell(110,4,utf8_decode($textoLogotipo), 1, 'C');
    $pdf->SetXY(120,33);
    $pdf->SetFont('Arial','',$fontSize);
    $idPedidoFormatado = str_pad((int)$dados['IDPEDIDO'][0],7,"0",STR_PAD_LEFT);
    $pdf->linha(80,14,'Nr. documento: '.$idPedidoFormatado,1,0,'C');
    $pdf->SetXY(120,47);
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->linha(80,14,'Data: '.$dados['DTPEDIDOFORMATADA'][0],1,0,'C');

    $pdf->SetXY(10,61);
    $pdf->SetFont('Arial','',$fontSize);
    $pdf->linha(132,$h,'Cliente: '.ucwords(strtolower($dados['NMCLIENTE'][0])),1,0);
    $cpfCnpj = $dados['NRCPFCNPJ'][0];
    if (strlen($cpfCnpj) == 11) {
        $cpfCnpj = mask($cpfCnpj,'###.###.###-##');
    } else {
        $cpfCnpj = mask($cpfCnpj,'##.###.###/####-##');
    }
    $pdf->linha(0,$h,'CPF/CNPJ: '.$cpfCnpj,1,1);
    $pdf->linha(0,$h,'Logradouro: '.ucwords(strtolower($dados['DSLOGRADOURO'][0])),1,1);
    $pdf->linha(132,$h,'Complemento: '.ucwords(strtolower($dados['DSCOMPLEMENTO'][0])),1,0);
    $pdf->linha(0,$h,'Telefone: '.$dados['NRTELEFONE'][0],1,1);
    $cep = empty($dados['DSCEP'][0]) ? null : mask($dados['DSCEP'][0],'##.###-###');
    $pdf->linha(70,$h,'CEP: '.$cep,1,0);
    $pdf->linha(0,$h,'Bairro: '.ucwords(strtolower($dados['DSBAIRRO'][0])),1,1);
    $pdf->linha(120,$h,'Cidade: '.ucwords(strtolower($dados['NMMUNICIPIO'][0])),1,0);
    $pdf->linha(0,$h,'UF: '.$dados['DSSIGLA'][0],1,1);    
}

// função chamada automaticamente pela classe TPDF quando existir
function rodape(TPDF $pdf)
{   
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
