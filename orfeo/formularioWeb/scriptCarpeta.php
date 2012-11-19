<?php

/*
 * Creado por Metro Cali S.A.
 * Martes 16 de Octubre de 2012, 14:19
 * Script para la creación de las carpetas que almacenan los documentos digitales
 * 
 * @autor Edgar Moncada
 * Practicante de Metro Cali S.A
 * 
 * 
 */

/* 
 * @param $anoRand la carpeta que indica el año de creación de los docuementos
 * @param $depeRadicaFormularioWeb indica la dependencia que lo creo o a la que esta dirigido el documento
 * 
 * Función que crea el conjunto de carpetas que se van radicando desde el 
 * formularioWeb. Si existen no se crea ni renombra nada. De lo contrario se va
 * construyendo dinamicamente.
 * 
 * El estandar de creación es Año de radicación y el numero de la dependencia.
 * En la carpeta bodega que esta en un nivel superior
 * Ej: raiz/bodega/2012/900/
 * Los permisos son: rwxr-xr-x del usuario apache y grupo apache
 */

function bodegaCrear($anoRad, $depeRadicaFormularioWeb) {

    $path_bodega = "../bodega/";

    if (file_exists($path_bodega . $anoRad)) {
        if (file_exists($path_bodega . $anoRad . "/" . $depeRadicaFormularioWeb)) {
            //existen las carpetas           
        } else {
            mkdir($path_bodega . "" . $anoRad . "/" . $depeRadicaFormularioWeb);
        }
    } else {
        mkdir($path_bodega . "" . $anoRad);
        mkdir($path_bodega . "" . $anoRad . "/" . $depeRadicaFormularioWeb);
    }
}

/* 
 * @param $anoRand la carpeta que indica el año de creación de los docuementos
 * @param $depeRadicaFormularioWeb indica la dependencia que lo creo o a la que esta dirigido el documento
 * @param $carpanexos indica la carpeta donde se almacenan los anexos
 * 
 * Función que crea el conjunto de carpetas que se van radicando desde el 
 * formularioWeb para los anexos. Si existen no se crea ni renombra nada. De lo contrario se va
 * construyendo dinamicamente. Llama a bodegaCrear.
 * 
 * El estandar de creación es Año de radicación y el numero de la dependencia y la carpeta docs.
 * En la carpeta bodega que esta en un nivel superior
 * Ej: raiz/bodega/2012/900/
 * Los permisos son: rwxr-xr-x del usuario apache y grupo apache
 */
function bodegaCrearAnexos($anoRad, $depeRadicaFormularioWeb, $carpanexos) {
    if(!$carpanexos || $carpanexos=='') $carpanexos= "docs";
    bodegaCrear($anoRad, $depeRadicaFormularioWeb);    
    $path_bodega = "../bodega/".$anoRad."/".$depeRadicaFormularioWeb."/";

    if (file_exists($path_bodega . $carpanexos)) {
        //existe la carpeta
    } else {
        mkdir($path_bodega . $carpanexos);        
    }
}

?>
