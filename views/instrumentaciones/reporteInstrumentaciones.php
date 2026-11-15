<?php

class PDF extends FPDF{

    function Footer(){
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,10,'Fecha: '.date('d-m-Y'),0,0);
    }

}

$pdf = new PDF();


$pdf->AliasNbPages();
$pdf->AddPage();

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(190, 10, $datos['nombre_carrera'], 1, 0, 'C');
$pdf->Ln(10);
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(190,17,"REPORTE {$tituloReporte}    Bloque:{$datos['bloque']}    {$datos['periodo']}",1,0,'C');
$pdf->Ln(10);
$porcentaje = (int)($datos['total_entregadas'] *100);
$porcentaje = round($porcentaje / (int)$datos['total']);

$textoGlobal = "Total: {$datos['total']}           ";
$textoGlobal .= "Entregado: {$porcentaje}%           ";
$textoGlobal .= "Entregadas: {$datos['total_entregadas']}";
$pdf->Cell(0,6,$textoGlobal,0,0,'C');
//$pdf->Cell(0,5,'Total: '.$datos['total'],0,1);
//$pdf->Cell(20,5,'Entregado: '.$porcentaje.'%',0,1);
//$pdf->Cell(20,5,'Entregadas: '.$datos['total_entregadas'],0,1);

$pdf->Ln(10);

$pdf->SetFont('Times','B',14);

$pdf->Cell(0,5,'DOCENTES',0,1,'C');
$pdf->Ln('8');
$pdf->SetFont('Times','',12);

foreach ($datos['datos_docentes'] AS $docente){
    $porce = (int) ($docente['entregadas'] * 100 );
    $porce = round( $porce / (int) $docente['total']);
    $text = 'Total: '.$docente['total'].'           Entregado: '.$porce;
    $text .= '%           Entregadas: ';
    $text .= $docente['entregadas'];
    $pdf->Cell(0,5,$docente['nombre'],0,1,'');
    $pdf->Cell(0,5,$text,0,1,'');
    $pdf->Ln('5');

}

$pdf->Output('archivos/REPORTE_'.$datos['nombre_carrera'].'_INSTRUMENTACIONES.pdf','F');

header('Location: '.RUTA_PROYECTO.'archivos/REPORTE_'.$datos['nombre_carrera'].'_INSTRUMENTACIONES.pdf');
exit;
