<?php
    require_once('report_table.php');
    require_once "./modelos/mainModel.php";
    date_default_timezone_set('UTC-04:00');


    //Conexion y consulta a la Base de Datos
    $main = new mainModel();
    $consulta = "SELECT medicinas.`Codigo`, medicinas.`Nombre`, medicinas.`Detalle`, medicinas.`Laboratorio`, proveedores.`Nombre` AS 'NombreProv', presentaciones.`Nombre` AS 'NombrePres', lotes.`Stock`,
    lotes.`FechaVencimiento`, lotes.`PrecioCompra`, presentaciones.`PrecioVenta`
    FROM medicinas 
    INNER JOIN proveedores ON medicinas.IdProveedores = proveedores.IdProveedores
    INNER JOIN presentaciones ON medicinas.IdMedicinas = presentaciones.`IdMedicinas`
    INNER JOIN lotes ON presentaciones.`IdPresentaciones` = lotes.`IdPresentaciones`
    WHERE lotes.`Estado` = '1' AND medicinas.Estado = '1' AND proveedores.`Estado` = '1'
    ORDER BY medicinas.Nombre ASC";
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    
    
    //Crear Página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('L','LETTER',0);
    $pdf->AliasNbPages();
    
    //Tamaño de las Columnas
    $pdf->SetWidths(array(30,65,30,30,20,30,25,25));
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
    $pdf->SetLineHeight(8);

    //Titulo de la Página
    $pdf->SetFont('Times','BU',18);
    // Movernos a la derecha
    $pdf->Cell(120);
    // Título
    $pdf->Cell(30,10,'REPORTE DE MEDICINAS',0,0,'C');
    // Salto de línea
    $pdf->Ln(20);

    //Inicio de la Tabla
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);

    $pdf->Cell(30,24,utf8_decode('Código'),1,0,'C',true);
    $pdf->Cell(65,24,'Nombre',1,0,'C',true);
    //$pdf->Cell(80,24,'Detalle',1,0,'C',true);
    $pdf->Cell(30,24,'Laboratorio',1,0,'C',true);
    $pdf->Cell(30,24,'Proveedor',1,0,'C',true);
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->Cell(50,$c = 12,'Lote',1,0,'C',true);
    $pdf->SetXY($x, $y + $c);
    //$pdf->Cell(30,12,utf8_decode('Presentación'),1,0,'C',true);
    $pdf->Cell(20,12,'Stock',1,0,'C',true);
    $pdf->Cell(30,12,'Fecha Vcto.',1,0,'C',true);
    $x = $pdf->GetX();
    $y = $pdf->GetY() - $c;
    $pdf->SetXY($x, $y);
    $pdf->MultiCell($c = 25,8,'Precio     Compra    (Bolivianos)',1,0,'C',true);
    $pdf->SetXY($x + $c, $y);
    $pdf->MultiCell(25,8,'Precio     Venta     (Bolivianos)',1,0,'C',true);
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);
    
    foreach($datos as $dato){
        $pdf->Row(Array($dato['Codigo'],$dato['Nombre'].' ( '.$dato['NombrePres'].' )',$dato['Laboratorio'],$dato['NombreProv'],$dato['Stock'],$dato['FechaVencimiento'],$dato['PrecioCompra'],$dato['PrecioVenta'],));
    }

    $pdf->Output('D','Reporte de Medicinas '.date("m.d.y, g:i:s a").'.pdf',false);
?>