<?php
    require_once('report_table.php');
    require_once "./modelos/mainModel.php";
    date_default_timezone_set('UTC-04:00');

    $i=1;
    //Conexion y consulta a la Base de Datos
    $main = new mainModel();
    $consulta = "SELECT * FROM servicios WHERE EstadoBD = '1' ORDER BY Nombre ASC";
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    
    //Crear Página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('L','LETTER',0);
    $pdf->AliasNbPages();
    
    //Tamaño de las Columnas
    $pdf->SetWidths(array(20,25,80,100,30));
    $pdf->SetAligns(array('C','C','C','C','C'));
    $pdf->SetLineHeight(8);

    //Titulo de la Página
    $pdf->SetFont('Times','BU',18);
    // Movernos a la derecha
    $pdf->Cell(120);
    // Título
    $pdf->Cell(30,10,'REPORTE DE SERVICIOS',0,0,'C');
    // Salto de línea
    $pdf->Ln(20);

    //Inicio de la Tabla
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);

    $pdf->Cell(20,16,utf8_decode('Nro.'),1,0,'C',true);
    $pdf->Cell(25,16,utf8_decode('Código'),1,0,'C',true);
    $pdf->Cell(80,16,'Nombre',1,0,'C',true);
    $pdf->Cell(100,16,'Detalle',1,0,'C',true);
    $pdf->MultiCell(30,8,'Precio       (Bolivianos)  ',1,0,'C',true);
    //$pdf->Ln();

    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);
    foreach($datos as $dato){
        $pdf->Row(Array($i,utf8_decode($dato['Codigo']),utf8_decode($dato['Nombre']),utf8_decode($dato['Detalle']),utf8_decode($dato['Precio']),));
        $i = $i + 1;
    }

    $pdf->Output('D','Reporte de Servicios '.date("m.d.y, g:i:s a").'.pdf',false);
?>