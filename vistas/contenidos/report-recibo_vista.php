<?php
    require_once "./modelos/mainModel.php";
    require_once('report_table.php');
    date_default_timezone_set('UTC-04:00');
    $main = new mainModel();
    $array = explode("/", $_GET["views"]);
    $idVenta = $array[1];
    $idVenta = $main->decryption($idVenta);

    
    
    ////////////////////////////////////////////////
    //Conexion y consulta a la Base de Datos
    $consulta = "SELECT clientes.`Nombres`, clientes.`Apellidos`, empleados.Usuario, clientes.`Direccion`, clientes.`Telefono`, ventas.`Fecha`
    FROM ventas
    INNER JOIN clientes ON ventas.`IdClientes` = clientes.`IdClientes`
    INNER JOIN empleados ON ventas.`IdEmpleados` = empleados.`IdEmpleados`
    WHERE ventas.`Estado` = '1' AND clientes.`Estado` = '1' AND  empleados.`Estado` = '1' AND ventas.`IdVentas` = ".$idVenta;
    $conexion = $main->conectar();
    $datos = $conexion->query($consulta);
    $datos = $datos->fetchAll();
    $venta = array('','','','','','');
    foreach($datos as $dato){
        $venta[0] = $dato['Nombres'];
        $venta[1] = $dato['Apellidos'];
        $venta[2] = $dato['Direccion'];
        $venta[3] = $dato['Telefono'];
        $venta[4] = $dato['Fecha'];
        $venta[5] = $dato['Usuario'];
    }
    ////////////////////////////////////////////////
    //Creacion de página
    $pdf=new PDF_MC_Table();
    $pdf->AddPage('P','LETTER',$unit='mm');
    $pdf->AliasNbPages();
    $pdf->Image('./vistas/assets/avatar/Avatar.png',10,8,33);
    $pdf->SetFont('Times','BU',20);   
    $textypos = 5;
    $pdf->setY(15);
    $pdf->setX(95);
    // Agregamos los datos de la empresa
    $pdf->Cell(5,$textypos,"RECIBO");
    
    $pdf->SetFont('Arial','B',10);    
    $pdf->setY(50);$pdf->setX(10);
    $pdf->Cell(5,$textypos,"DE:");
    $pdf->SetFont('Arial','',10);    
    $pdf->setY(55);$pdf->setX(10);
    $pdf->Cell(5,$textypos,"Juanky's Adiestramiento Canino");
    $pdf->SetFont('Arial','B',10); 
    $pdf->setY(60);$pdf->setX(10);
    $pdf->Cell(5,$textypos,"USUARIO:");
    $pdf->SetFont('Arial','',10); 
    $pdf->setY(65);$pdf->setX(10);
    $pdf->Cell(5,$textypos,utf8_decode($venta[5]));
    

    // Agregamos los datos del cliente
    $pdf->SetFont('Arial','B',10);    
    $pdf->setY(50);$pdf->setX(75);
    $pdf->Cell(5,$textypos,"PARA:");
    $pdf->SetFont('Arial','',10);    
    $pdf->setY(55);$pdf->setX(75);
    $pdf->Cell(5,$textypos,utf8_decode($venta[0]));
    $pdf->setY(60);$pdf->setX(75);
    $pdf->Cell(5,$textypos,utf8_decode($venta[1]));
    $pdf->setY(65);$pdf->setX(75);
    $pdf->Cell(5,$textypos,utf8_decode($venta[2]));
    $pdf->setY(70);$pdf->setX(75);
    $pdf->Cell(5,$textypos,utf8_decode($venta[3]));

    // Agregamos los datos del cliente
    $pdf->SetFont('Arial','B',10);    
    $pdf->setY(50);$pdf->setX(135);
    $pdf->Cell(5,$textypos,"FECHA :");
    $pdf->SetFont('Arial','',10);    
    $pdf->setY(55);$pdf->setX(135);
    $pdf->Cell(5,$textypos,$venta[4]);
    

    /// Apartir de aqui empezamos con la tabla de productos
    $pdf->setY(80);$pdf->setX(135);
        $pdf->Ln();
    /////////////////////////////
    //// Array de Cabecera
    $header = array("Código", "Descripción","Cantidad","Precio       (Bolivianos)  ","Total        (Bolivianos)  ");
    //// Arrar de Productos
    ///////////////////////////////////////////////////////////
    
    $consultaMedicinas = "SELECT medicinas.`Codigo`, medicinas.`Nombre`, presentaciones.`Nombre` AS NomPres, presentaciones.`PrecioVenta`, detalleventas.`Cantidad`
    FROM ventas
    INNER JOIN detalleventas ON ventas.`IdVentas` = detalleventas.`IdVentas`
    INNER JOIN medicinas ON detalleventas.`IdMedicinas` = medicinas.`IdMedicinas`
    INNER JOIN lotes ON detalleventas.IdLotes = lotes.IdLotes
    INNER JOIN presentaciones ON lotes.`IdPresentaciones` = presentaciones.`IdPresentaciones`
    WHERE ventas.`Estado` = '1' AND medicinas.`Estado` = '1' AND ventas.`IdVentas` = '".$idVenta."'";

    $consultaMed = $conexion->query($consultaMedicinas);
    $medicinas = $consultaMed->fetchAll();

    

    $consultaProductos = "SELECT articulos.`Codigo`, articulos.`Nombre`, articulos.`Fabricante`, articulos.`PrecioVenta`, detalleventas.`Cantidad`
    FROM ventas
    INNER JOIN detalleventas ON ventas.`IdVentas` = detalleventas.`IdVentas`
    INNER JOIN articulos ON detalleventas.`IdArticulos` = articulos.`IdArticulos`
    WHERE ventas.`Estado` = '1' AND articulos.`Estado` = '1' AND ventas.`IdVentas` = '".$idVenta."'";

    $consultaProd = $conexion->query($consultaProductos);
    $productos = $consultaProd->fetchAll();
    

    $consultaServicios = "SELECT servicios.`Codigo`, servicios.`Nombre`, servicios.`Precio`, detalleventas.`Cantidad`
    FROM ventas
    INNER JOIN detalleventas ON ventas.`IdVentas` = detalleventas.`IdVentas`
    INNER JOIN servicios ON detalleventas.`IdServicios` = servicios.`IdServicios`
    WHERE ventas.`Estado` = '1' AND servicios.`Estado` = '1' AND ventas.`IdVentas` = '".$idVenta."'";
   
    $consultaSer = $conexion->query($consultaServicios);
    $servicios = $consultaSer->fetchAll();
    
    ////////////////////////////////////////////////////////////
    //Color de Cabecera
    $pdf->SetFillColor(150);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',10);
    // Column widths
    $w = array(30, 95, 20, 25, 25);
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
    // Data
    $pdf->SetFillColor(224,235,255);
    $pdf->SetTextColor(0);
    $pdf->SetFont('Arial','',10);
    $total = 0;

    //Tamaño de las Columnas
    $pdf->SetWidths($w);
    $pdf->SetAligns(array('C','C','C','C','C'));
    $pdf->SetLineHeight(6);
    
    $contador = 0;
    foreach($medicinas as $medicina){
        $pdf->Row(Array(utf8_decode($medicina['Codigo']),utf8_decode($medicina['Nombre']).' ('.
                        utf8_decode($medicina['NomPres']).')',utf8_decode($medicina['Cantidad']),number_format($medicina['PrecioVenta'], 2, ".",","),
                        number_format($medicina['Cantidad']*$medicina['PrecioVenta'], 2, ".",","),));
        $total+= $medicina['Cantidad']*$medicina['PrecioVenta'];
        $contador ++;
    }
    foreach($productos as $producto){
        $pdf->Row(Array(utf8_decode($producto['Codigo']),utf8_decode($producto['Nombre']).' ('.utf8_decode($producto['Fabricante']).')',$producto['Cantidad'],number_format($producto['PrecioVenta'], 2, ".",","),
                        number_format($producto['Cantidad']*$producto['PrecioVenta'], 2, ".",","),));
        $total+= $producto['Cantidad']*$producto['PrecioVenta'];
        $contador ++;
    }
    foreach($servicios as $servicio){
        $pdf->Row(Array(utf8_decode($servicio['Codigo']),utf8_decode($servicio['Nombre']),$servicio['Cantidad'],number_format($servicio['Precio'], 2, ".",","),
                        number_format($servicio['Cantidad']*$servicio['Precio'], 2, ".",","),));
        $total+= $servicio['Cantidad']*$servicio['Precio'];
        $contador ++;
    }
    /////////////////////////////
    
    //// Apartir de aqui esta la tabla con los totales
    $yposdinamic = 100 + ($contador *6);

    $pdf->setY($yposdinamic);
    //$pdf->setX(250);
        $pdf->Ln();
    /////////////////////////////
    
    $header = array("", "");
    $data2 = array(
        array("Total", $total),
    );
        // Column widths
        $w2 = array(30, 30);
        // Header

        $pdf->Ln();
        // Data
        foreach($data2 as $row)
        {
            $pdf->setX(145);
            $pdf->Cell($w2[0],6,$row[0],1);
            $pdf->Cell($w2[1],6,"Bs. ".number_format($row[1], 2, ".",","),'1',0,'R');

            $pdf->Ln();
        }
    /////////////////////////////

    $yposdinamic += (count($data2)*10);

    $pdf->SetFont('Arial','',10);    

    $pdf->setY($yposdinamic+40);
    $pdf->setX(50);
    $pdf->Cell(40,$textypos,"Entregue Conforme",'T',0,'C');
    $pdf->setY($yposdinamic+40);
    $pdf->setX(120);
    $pdf->Cell(40,$textypos,"Recibi Conforme",'T',0,'C');


    $pdf->Output('D','Recivo del '.$venta[4].' '.date("m.d.y, g:i:s a").'.pdf',false);
    
?>