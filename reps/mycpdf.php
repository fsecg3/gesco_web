<?php
require_once('tcpdf.php');
class MYPDF extends TCPDF {

//Page header
public function Header() {
    // Title
    $this->SetFont('aealarabiya', 14);
            //$this->Ln();
            //$this->Cell(120, 15, 'UDBKM', 0, 1, 'C', false);
            $this->Cell(50, 15, 'كلية العلوم الإجتماعية والإنسانية', 0, 1, 'C', false);
            $this->Ln();
            $this->Cell(50, 15, 'كلية العلوم الإجتماعية والإنسانية', 0, 1, 'C', false);
            $this-->writeHTML("<hr>", true, false, false, false, 'aaaaaaaaaaaa');
            $page = $this->getAliasNumPage();
            if ($page == 1){
                //$this->Cell(0, 15, 'Page 1', 0, false, 'C', 0, '', 0, false, 'M', 'M');
                //$this->Ln();
            } else {
                $header = $page . '<< PV DE MATIERE >>';
                //$this->Cell(0, 15, $header, 2, false, 'C', 0, '', 0, false, 'M', 'M');
            }
}

// Page footer
public function Footer() {
    // Position at 15 mm from bottom
    $this->SetY(-15);
    // Set font
    $this->SetFont('helvetica', 'I', 8);
    // Page number
    $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
}
}

/*
$pdf = new MYPDF('L', 'mm', array(215.9, 279.4));
    $pdf->SetTitle('Manifest');
    $pdf->SetMargins(5, 5);
    $pdf->SetAutoPageBreak(TRUE);
    $pdf->SetPrintHeader(TRUE);
    $pdf->SetHeaderMargin(10);
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->AddPage();
        $pdf->AddPage();
        $pdf->AddPage();
        
         $file = 'Manifest.pdf';
  $pdf->Output($file);*/ 
?>