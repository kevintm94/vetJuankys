<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title><?php echo COMPANY; ?></title>
    <?php include "./vistas/include/link.php";?>
</head>
<body>
    <?php
        $peticionAjax = false;
        require_once "./controladores/vistasControlador.php";
        $IV = new vistasControlador();
        $vistas = $IV->obtener_vistas_controlador();
        if($vistas == "login" || $vistas == "404" || $vistas == "report-course" || $vistas == "report-client" || $vistas == "report-item" || $vistas == "report-provider" || $vistas == "report-service" || $vistas == "report-user" || $vistas == "report-recibo" || $vistas == "report-venta-venta" || $vistas == "report-medicine"){
            require_once "./vistas/contenidos/".$vistas."_vista.php";
        }else{
            session_start(['name' => 'Auth']);

            $pagina = explode("/", $_GET['views']);
            
            require_once "./controladores/loginControlador.php";
            $lc = new loginControlador();
            if (!isset($_SESSION['token_auth']) || !isset($_SESSION['usuario_auth']) || !isset($_SESSION['id_auth'])) {
                echo $lc->forzar_cierre_sesion_controlador();
                exit();
            }
    ?>
	<!-- Contenedor principal -->
	<main class="full-box main-container">
		<!-- Navegador lateral -->
		<?php include "./vistas/include/navLateral.php";?>

		<!-- Contenido de la página -->
		<section class="full-box page-content">
            <?php 
                include "./vistas/include/navBar.php";

                include $vistas;
            ?>
		</section>
    </main>
    <?php
            include "./vistas/include/logOut.php";
        }
        include "./vistas/include/script.php";
    ?>
</body>
</html>