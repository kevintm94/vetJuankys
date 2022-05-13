<?php
    require_once('report_table.php');
    require_once "./modelos/mainModel.php";
    date_default_timezone_set('UTC-04:00');

    $i=1;
    //Conexion y consulta a la Base de Datos
    $main = new mainModel();
    $consulta = "SELECT * FROM clientes WHERE Estado = '1' ORDER BY Nombres ASC";
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    
    //Crear Página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('P','LETTER',0);
    $pdf->AliasNbPages();
    
    //Tamaño de las Columnas
    $pdf->SetWidths(array(15,40,40,30,25,45));
    $pdf->SetAligns(array('C','C','C','C','C','C'));
    $pdf->SetLineHeight(8);

    //Titulo de la Página
    $pdf->SetFont('Times','BU',18);
    // Movernos a la derecha
    $pdf->Cell(80);
    // Título
    $pdf->Cell(30,10,'REPORTE DE CLIENTES',0,0,'C');
    // Salto de línea
    $pdf->Ln(20);

    //Inicio de la Tabla
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);

    $pdf->Cell(15,8,utf8_decode('Nro.'),1,0,'C',true);
    $pdf->Cell(40,8,'Nombre',1,0,'C',true);
    $pdf->Cell(40,8,'Apellido',1,0,'C',true);
    $pdf->Cell(30,8,'CI',1,0,'C',true);
    $pdf->Cell(25,8,utf8_decode('Teléfono'),1,0,'C',true);
    $pdf->Cell(45,8,utf8_decode('Dirección'),1,0,'C',true);
    $pdf->Ln();
    
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',12);
    foreach($datos as $dato){
        $pdf->Row(Array($i,utf8_decode($dato['Nombres']),utf8_decode($dato['Apellidos']),utf8_decode($dato['CI']),utf8_decode($dato['Telefono']),utf8_decode($dato['Direccion']),));
        $i = $i + 1;
    }

    $pdf->Output('D','Reporte de Clientes '.date("m.d.y, g:i:s a").'.pdf',false);
?>