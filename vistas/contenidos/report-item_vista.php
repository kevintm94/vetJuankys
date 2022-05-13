<?php
    require_once('report_table.php');
    require_once "./modelos/mainModel.php";
    date_default_timezone_set('UTC-04:00');

    $i=1;
    //Conexion y consulta a la Base de Datos
    $main = new mainModel();
    $consulta = "SELECT * FROM articulos WHERE Estado = '1' ORDER BY Nombre ASC";
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    
    //Crear Página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('L','LETTER',0);
    $pdf->AliasNbPages();
    
    //Tamaño de las Columnas
    $pdf->SetWidths(array(15,28,45,70,30,20,25,25));
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
    $pdf->SetLineHeight(8);

    //Titulo de la Página
    $pdf->SetFont('Times','BU',18);
    // Movernos a la derecha
    $pdf->Cell(120);
    // Título
    $pdf->Cell(30,10,'REPORTE DE ARTICULOS',0,0,'C');
    // Salto de línea
    $pdf->Ln(20);

    //Inicio de la Tabla
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);

    
    $pdf->Cell(15,24,utf8_decode('Nro.'),1,0,'C',true);
    $pdf->Cell(28,24,utf8_decode('Código'),1,0,'C',true);
    $pdf->Cell(45,24,'Nombre',1,0,'C',true);
    $pdf->Cell(70,24,'Detalle',1,0,'C',true);
    $pdf->Cell(30,24,'Fabricante',1,0,'C',true);
    $pdf->Cell(20,24,'Stock',1,0,'C',true);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell($c = 25,8,'Precio     Compra    (Bolivianos)',1,0,'C',true);
    $pdf->SetXY($x + $c, $y);
    $pdf->MultiCell(25,8,'Precio     Venta     (Bolivianos)',1,0,'C',true);
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);
    foreach($datos as $dato){
        $pdf->Row(Array($i,utf8_decode($dato['Codigo']),utf8_decode($dato['Nombre']),utf8_decode($dato['Detalle']),utf8_decode($dato['Fabricante']),utf8_decode($dato['Stock']),utf8_decode($dato['PrecioCompra']),utf8_decode($dato['PrecioVenta']),));
        $i = $i + 1;
    }

    $pdf->Output('D','Reporte de Productos '.date("m.d.y, g:i:s a").'.pdf',false);
?>