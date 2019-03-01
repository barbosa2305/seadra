<?php

class RelOrcamento extends TPDF
{
    private $dados;
    //private $idPedido;
    private $cellHeight = 4;
    private $gridFontTipo = 'Arial';
    private $gridFontTamanho = 8;
    private $gridFontCor = 'Black';
    
    public function getDados()
    {
        return $this->dados;
    }
    public function setDados($dados)
    {
        $this->dados = $dados;
    }
    /*
    public function getIdPedido()
    {
        return $this->idPedido;
    }
    public function setIdPedido($idPedido)
    {
        $this->idPedido = $idPedido;
    }
    */
    public function getCellHeight()
    {
        return $this->cellHeight;
    }    
    public function setCellHeight($cellHeight)
    {
        $this->cellHeight = $cellHeight;
    }

    public function linha($w,$h=0,$txt='',$border=0,$ln=0,$align='L',$fill=false){
        $txt = utf8_decode(trim($txt));
        $this->cell($w,$h,$txt,$border,$ln,$align,$fill);
    }
    public function estiloTexto(){
        $this->SetFont('Arial','',8);
        $this->SetTextColor(0,0,0);
    }
    public function tituloH1($label)
    {
        // Title
        $this->Ln(6);
        $this->SetFont('Arial','',11);
        //$this->SetFillColor(200,220,255);
        $this->SetFillColor(105,105,105);
        $this->SetTextColor(255,255,255);
        $this->linha(0,$this->getCellHeight()+2,"$label",0,1,'L',true);
        $this->estiloTexto();
        $this->Ln(1);
        // Save ordinate
        $this->y0 = $this->GetY();
    }

    public function convertArrayUtf8ParaIso($arrayUtf8){
        $arrayIso = null;
        if( !is_array($arrayUtf8) ){
            $arrayIso = $arrayUtf8;
        }else{
            foreach( $arrayUtf8 as $key => $arr ) {
                foreach( $arr as $index => $value ) {
                    $arrayIso[$key][$index] = strtoupper( utf8_decode($value) );
                }
            }
        }
        return $arrayIso;
    }

    public function itens()
    {
        //$this->tituloH1('Itens');
        $dados =$this->getDados();

        $dados = $this->convertArrayUtf8ParaIso($dados);
        $tipo = $this->gridFontTipo;
        $tamanho = $this->gridFontTamanho;
        $fontCor = $this->gridFontCor;

        $this->clearColumns();
        $this->setData( $dados );
        $this->addColumn(utf8_decode('id Item'),10,'C', 'NRANO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('id Produto'),20,'L', 'IDPRODUTO'	,null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Quantidade'),60 ,'L', 'QTITEMPDIDO',null,null,$tamanho,$fontCor,$tipo);
        $this->addColumn(utf8_decode('Preço'),20,'C', 'PRECO',null,null,$tamanho,$fontCor,$tipo);
        $this->printRows();
    }
}

if ( !ArrayHelper::validateUndefined($_REQUEST,'IDPEDIDO') ){
    echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
} else {
    $idPedido = $_REQUEST['IDPEDIDO'];
    $where = array( 
                    'IDPEDIDO'=>$idPedido
                    ,'STCLIENTEATIVO'=>STATUS_ATIVO
                    ,'STPRODUTOATIVO'=>STATUS_ATIVO
                  );
    $orderBy = 'NMPRODUTO';
    $dados = Pedido::selectRelOrcamento( $orderBy,$where );
    if ( empty($dados) ){
        echo Mensagem::RELATORIO_DADOS_INEXISTENTES;
    } else {
        $pdf = new RelOrcamento('P');
        //$pdf->setIdPedido($idPedido);
        $pdf->setDados($dados);
        $pdf->AddPage();
        $pdf->itens(); 
        $pdf->show();
    }
}

// função chamada automaticamente pela classe TPDF quando existir
function cabecalho(TPDF $pdf)
{
    $dados = $pdf->getDados();

    $pdf->Ln(5);
    $pdf->SetFont('Arial', 'B', 11);
    $pdf->linha(0, 6, 'PEDIDO DE VENDA', 1, 1, 'C');
    $textoFixo = "NÃO É DOCUMENTO FISCAL - NÃO É VÁLIDO COMO RECIBO E COMO GARANTIA DE MERCADORIA - NÃO COMPROVA PAGAMENTO";
    $pdf->SetFont('Arial',null,6);
    $pdf->MultiCell(150, 6, utf8_decode($textoFixo), 1, 'C');
    $pdf->SetXY(160,21);
    $pdf->SetFont('Arial', null, 9);
    $pdf->linha(0, 6, 'Página: '.$pdf->PageNo().'/{nb}', 1, 1, 'C');

    $pdf->SetFont('Arial', null, 9);
    $empresa = 'MARTINS TEXTIL LTDA';
    $emitente = 'Emitente: '.$empresa;
    $cnpjEmitente = 'CNPJ: 07.882.295/0001-16';
    $pdf->linha(125, 6, $emitente, 1, 0);
    $pdf->linha(0, 6, $cnpjEmitente, 1, 1);

    $enderecoEmitente = "Rua dos Missionários, 643\nQd.31, Lt.22 - St. Rodoviário - Goiânia - GO\nTelefone: (62) 3271-1912\n ";
    $textoLogotipo = "\n".$empresa."\n\n".$enderecoEmitente;
    $pdf->MultiCell(110,4,utf8_decode($textoLogotipo), 1, 'C');
    $pdf->SetXY(120,33);
    $pdf->SetFont('Arial', null, 10);
    $idPedidoFormatado = str_pad((int)$dados['IDPEDIDO'][0],10,"0",STR_PAD_LEFT);
    $pdf->linha(80,14,'Nr. documento: '.$idPedidoFormatado,1,0,'C');
    $pdf->SetXY(120,47);
    $pdf->SetFont('Arial', null, 10);
    $pdf->linha(80,14,'Data: '.$dados['DTPEDIDO'][0],1,0,'C');

    $pdf->SetXY(10,61);
    $pdf->SetFont('Arial', null, 9);
    $pdf->linha(132,6,'Cliente: '.strtoupper($dados['NMCLIENTE'][0]),1,0);
    $cpfCnpj = $dados['NRCPFCNPJ'][0];
    if (strlen($cpfCnpj) == 11) {
        $cpfCnpj = mask($cpfCnpj,'###.###.###-##');
    } else {
        $cpfCnpj = mask($cpfCnpj,'##.###.###/####-##');
    }
    $pdf->linha(0,6,'CPF/CNPJ: '.$cpfCnpj,1,1);
    $pdf->linha(0,6,'Logradouro: '.ucwords(strtolower($dados['DSLOGRADOURO'][0])),1,1);
    $pdf->linha(132,6,'Complemento: '.ucwords(strtolower($dados['DSCOMPLEMENTO'][0])),1,0);
    $pdf->linha(0,6,'Telefone: '.$dados['NRTELEFONE'][0],1,1);
    $cep = empty($dados['DSCEP'][0]) ? null : mask($dados['DSCEP'][0],'##.###-###');
    $pdf->linha(70,6,'CEP: '.$cep,1,0);
    $pdf->linha(0,6,'Bairro: '.ucwords(strtolower($dados['DSBAIRRO'][0])),1,1);
    $pdf->linha(120,6,'Cidade: '.ucwords(strtolower($dados['NMMUNICIPIO'][0])),1,0);
    $pdf->linha(0,6,'UF: '.$dados['DSSIGLA'][0],1,1);    
}
// função chamada automaticamente pela classe TPDF quando existir
function rodape(TPDF $pdf)
{   
    $dados = $pdf->getDados();

    $pdf->SetFont('Arial', null, 9);
    $pdf->linha(132,6,'Total dos produtos(R$): ',1,0,'R');
    $pdf->linha(0,6,$dados['VLTOTAL'][0],1,1,'R');
    $pdf->linha(132,6,'Desconto(R$): ',1,0,'R');
    $pdf->linha(0,6,$dados['VLDESCONTO'][0],1,1,'R');
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->linha(132,6,'Total(R$): ',1,0,'R');
    $pdf->linha(0,6,$dados['VLPAGO'][0],1,1,'R');

    $pdf->Ln(12);
    $pdf->SetFont('Arial',null,10);
    $pdf->linha(56,6,'',0,0,'L');
    $pdf->linha(80,6,'Assinatura do cliente','T',0,'C');  
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
