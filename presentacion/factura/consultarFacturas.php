<?php
require("fpdf/fpdf.php");
include("phpqrcode/qrlib.php");

$idDueño = $_SESSION["id"];
$paseos = Paseo::consultarPorDueño($idDueño); 

$pdf = new FPDF('P', 'mm', 'Letter');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'REPORTE DE PASEOS', 0, 1, 'C');

$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(50, 50, 50);
$pdf->SetTextColor(255);
$pdf->Cell(40, 10, 'Paseador', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Fecha', 1, 0, 'C', true);
$pdf->Cell(30, 10, 'Tarifa', 1, 0, 'C', true);
$pdf->Cell(40, 10, 'Perro', 1, 0, 'C', true);
$pdf->Cell(50, 10, 'Factura QR', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0);

foreach ($paseos as $p) {
    $paseador = $p->getPaseador()->getNombre();
    $fecha = date('d-m-Y', strtotime($p->getFecha()));
    $tarifa = "$" . number_format($p->getTarifa(), 0, ',', '.');
    $perro = $p->getPerro()->getNombre();
    
    // Generar contenido QR
    $contenidoQR = "Factura del paseo con $perro\n"
    . "Paseador: $paseador\n"
    . "Fecha: $fecha\n"
    . "Valor: $tarifa COP\n"
    . "Paga en: https://paseaperros.com/pago/" . $p->getIdPaseo();
    
    $nombreQR = "tmp_qr_" . uniqid() . ".png";
    QRcode::png($contenidoQR, $nombreQR, QR_ECLEVEL_L, 2);
    
    $altura = 35;
    
    // Verificar si cabe en la página
    if ($pdf->GetY() + $altura > $pdf->GetPageHeight() - 10) {
        $pdf->AddPage();
    }
    
    $xInicio = $pdf->GetX();
    $yInicio = $pdf->GetY();
    
    // Dibujar manualmente cada celda con SetXY
    $pdf->SetXY($xInicio, $yInicio);
    $pdf->Cell(40, $altura, $paseador, 1, 0, 'C');
    
    $pdf->SetXY($xInicio + 40, $yInicio);
    $pdf->Cell(30, $altura, $fecha, 1, 0, 'C');
    
    $pdf->SetXY($xInicio + 70, $yInicio);
    $pdf->Cell(30, $altura, $tarifa, 1, 0, 'C');
    
    $pdf->SetXY($xInicio + 100, $yInicio);
    $pdf->Cell(40, $altura, $perro, 1, 0, 'C');
    
    $pdf->SetXY($xInicio + 140, $yInicio);
    $pdf->Cell(50, $altura, '', 1, 0, 'C');
    
    // Insertar QR dentro de su celda con margen interno
    $pdf->Image($nombreQR, $xInicio + 145, $yInicio + 3, 30, 30);
    
    $pdf->SetY($yInicio + $altura); // bajar cursor a la siguiente fila
    unlink($nombreQR);
}


if (ob_get_length()) {
    ob_clean();
}

$pdf->Output();
exit;
?>
