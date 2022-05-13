<?php
    require_once('report_table.php');
    require_once "./modelos/mainModel.php";
    date_default_timezone_set('UTC-04:00');
    $fecha_inicio = $_POST["fecha_inicio"];
    $fecha_fin = $_POST["fecha_final"];
    

    $contador=1;
    //Conexion y consulta a la Base de Datos
    $main = new mainModel();
    $consulta = "SELECT ventas.`IdVentas`
    FROM ventas
    WHERE ventas.`Fecha` >= '".$fecha_inicio."' AND ventas.`Fecha` <= '".$fecha_fin."' 
    ORDER BY ventas.Fecha ASC";
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    
    //Crear Página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('L','LETTER',0);
    $pdf->AliasNbPages();
    
    //Tamaño de las Columnas
    $pdf->SetWidths(array(15,45,62,40,20,25,25,25));
    $pdf->SetAligns(array('C','C','C','C','C','C','C','C'));
    $pdf->SetLineHeight(8);

    //Titulo de la Página
    $pdf->SetFont('Times','BU',18);
    // Movernos a la derecha
    $pdf->Cell(120);
    // Título
    $pdf->Cell(10,10,'REPORTE DE VENTAS'.$datos['IdVentas'],0,0,'C');
    // Salto de línea
    $pdf->Ln(20);
        
    $pdf->SetFont('Arial','',10);
    $pdf->Cell(90,8,'DEL : '.$fecha_inicio.'  HASTA: '.$fecha_fin);
    $pdf->Ln(10);
    //// Array de Cabecera
    $header = array("Nro.","Nombre", "Descripción","Cliente","Cantidad","Fecha","Precio       (Bolivianos)  ","Total         (Bolivianos)  ");
        
        //Color de Cabecera
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',10);
    // Column widths
    $w = array(15, 45, 62, 40, 20, 25, 25,25);
    // Header
    $x = $pdf->GetX();
    $y = $pdf->GetY();   
    for($i=0;$i<count($header);$i++){
        if($i < count($header) - 2){
            $pdf->Cell($w[$i],14,utf8_decode($header[$i]),1,0,'C','true');
            $x += $w[$i];
        }
        else{
            $pdf->MultiCell($w[$i],7,$header[$i],1,0,'C','true');
            $x += $w[$i];
            $pdf->setY($y);$pdf->setX($x);
        }
    }
    $pdf->Ln();
    $pdf->Ln();

    //Tabla  de Datos
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',10);

    foreach($datos as $dato){
        
        ///////////////////////////////////////////////////////////
    
        $consultaMedicinas = "SELECT medicinas.`Nombre`, presentaciones.`Nombre` AS NomPres, medicinas.`Detalle`,
          clientes.`Nombres`, clientes.`Apellidos`, presentaciones.`PrecioVenta`, detalleventas.`Cantidad`, ventas.`Fecha`
          FROM ventas
          INNER JOIN detalleventas
            ON ventas.`IdVentas` = detalleventas.`IdVentas`
          INNER JOIN medicinas
            ON detalleventas.`IdMedicinas` = medicinas.`IdMedicinas`
            INNER JOIN lotes
            ON detalleventas.IdLotes = lotes.IdLotes
          INNER JOIN presentaciones
            ON lotes.`IdPresentaciones` = presentaciones.`IdPresentaciones`
          INNER JOIN clientes
            ON ventas.`IdClientes` = clientes.`IdClientes`
        WHERE ventas.`Estado` = '1'
          AND medicinas.`Estado` = '1' AND ventas.`IdVentas` = ".$dato['IdVentas'];

        $consultaMed = $conexion->query($consultaMedicinas);
        $medicinas = $consultaMed->fetchAll();



        $consultaProductos = "SELECT articulos.`Nombre`, articulos.`Detalle`, clientes.`Nombres` , clientes.`Apellidos`, 
	    articulos.`Fabricante`, articulos.`PrecioVenta`, detalleventas.`Cantidad`, ventas.`Fecha`
        FROM ventas
        INNER JOIN detalleventas ON ventas.`IdVentas` = detalleventas.`IdVentas`
        INNER JOIN articulos ON detalleventas.`IdArticulos` = articulos.`IdArticulos`
        INNER JOIN clientes ON ventas.`IdClientes` = clientes.`IdClientes`
        WHERE ventas.`Estado` = '1' AND articulos.`Estado` = '1' AND ventas.`IdVentas` = ".$dato['IdVentas'];

        $consultaProd = $conexion->query($consultaProductos);
        $productos = $consultaProd->fetchAll();


        $consultaServicios = "SELECT servicios.`Nombre`,servicios.`Detalle`, clientes.`Nombres`,clientes.`Apellidos`, servicios.`Precio`, detalleventas.`Cantidad`, ventas.`Fecha`
        FROM ventas
        INNER JOIN detalleventas ON ventas.`IdVentas` = detalleventas.`IdVentas`
        INNER JOIN servicios ON detalleventas.`IdServicios` = servicios.`IdServicios`
        INNER JOIN clientes ON ventas.`IdClientes` = clientes.`IdClientes`
        WHERE ventas.`Estado` = '1' AND servicios.`Estado` = '1' AND ventas.`IdVentas` = ".$dato['IdVentas'];

        $consultaSer = $conexion->query($consultaServicios);
        $servicios = $consultaSer->fetchAll();

        ////////////////////////////////////////////////////////////
        foreach($medicinas as $medicina){
        $pdf->Row(Array($contador,utf8_decode($medicina['Nombre']).' ('.utf8_decode($medicina['NomPres']).')',
                        utf8_decode($medicina['Detalle']),
                        utf8_decode($medicina['Nombres']).' '.utf8_decode($medicina['Apellidos']),
                        utf8_decode($medicina['Cantidad']),
                        $medicina['Fecha'],
                        number_format($medicina['PrecioVenta'], 2, ".",","),
                        number_format($medicina['Cantidad']*$medicina['PrecioVenta'], 2, ".",","),));
    
            $contador ++;
        }
        foreach($productos as $producto){
        $pdf->Row(Array($contador,utf8_decode($producto['Nombre']).' ('.utf8_decode($producto['Fabricante']).')',
                        utf8_decode($producto['Detalle']),
                        utf8_decode($producto['Nombres']).' '.utf8_decode($producto['Apellidos']),
                        $producto['Cantidad'],
                        $producto['Fecha'],
                        number_format($producto['PrecioVenta'], 2, ".",","),
                        number_format($producto['Cantidad']*$producto['PrecioVenta'], 2, ".",","),));
        
            $contador ++;
        }
        foreach($servicios as $servicio){
            $pdf->Row(Array($contador,utf8_decode($servicio['Nombre']),
                            utf8_decode($servicio['Detalle']),
                            utf8_decode($servicio['Nombres']).' '.utf8_decode($servicio['Apellidos']),
                            $servicio['Cantidad'],
                            $servicio['Fecha'],
                            number_format($servicio['Precio'], 2, ".",","),
                            number_format($servicio['Cantidad']*$servicio['Precio'], 2, ".",","),));
            
            $contador ++;
        }
    }

    $pdf->Output('D','Reporte de Ventas '.'desde: '.$fecha_inicio.'  hasta: '.$fecha_fin.' '.date("m.d.y, g:i:s a").'.pdf',false);
?>