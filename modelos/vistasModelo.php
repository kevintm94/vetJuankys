<?php
    class vistasModelo{
        /*------Modelo para obtener vistas------*/
        protected static function obtener_vistas_modelo($vistas){
            $listaReporte = ["report-client",
                            "report-item",
                            "report-provider",
                            "report-service",
                            "report-user",
                            "report-course",
                            "report-recibo",
                            "report-venta-venta",
                            "report-medicine"
            ];
            $listaBlanca = ["client-list", 
                            "client-new", 
                            "client-search", 
                            "client-update", 
                            "company",
                            "course-list", 
                            "course-new", 
                            "course-search", 
                            "course-update",
                            "home", 
                            "item-list", 
                            "item-new", 
                            "item-search", 
                            "item-update",
                            "medicine-list", 
                            "medicine-new", 
                            "medicine-search", 
                            "medicine-update",
                            "provider-list",
                            "provider-new",
                            "provider-search",
                            "provider-update",
                            "report",
                            "report-venta",
                            "reservation-list", 
                            "reservation-new", 
                            "reservation-pending", 
                            "reservation-reservation", 
                            "reservation-search", 
                            "reservation-update", 
                            "service-list", 
                            "service-new", 
                            "service-search", 
                            "service-update", 
                            "user-list", 
                            "user-new", 
                            "user-search", 
                            "user-update"
                        ];
            if(in_array($vistas, $listaBlanca)){
                if(is_file("./vistas/contenidos/".$vistas."_vista.php")){
                    $contenido = "./vistas/contenidos/".$vistas."_vista.php";
                }else{
                    if (in_array($vistas, $listaReporte)) {
                        $contenido = $vistas;
                    } else {
                        $contenido = "404";
                    }
                }
            }elseif($vistas == "login" || $vistas == "index"){
                $contenido = "login";
            }else{
                if (in_array($vistas, $listaReporte)) {
                    $contenido = $vistas;
                } else {
                    $contenido = "404";
                }
            }
            return $contenido;
        }
    }
?>