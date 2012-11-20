<?

/*
 * Creado por Metro Cali S.A.
 * Martes 16 de Octubre de 2012, 14:19
 * Script para el anexo de documentos para el formulario Web
 * Esta funcion esta basado en el archivo raiz/upload2.php que es el empleado
 * para anexar documentos desde el formulario de Orfeo
 * 
 * @autor Edgar Moncada
 * Practicante de Metro Cali S.A 
 * 
 */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

/* 
 * @param $numeroRadicado el numero de radicado a anexar
 * @param $krdWeb el codigo del usuario al que se envia (el usuario web. filtrador)
 * @param $dependenciaWeb la dependencia del usuario web (900)
 * @param $radicado_rem opcional sgd_dir_direcciones el valor sgd_dir_tipo(ciudadano valor = 1)
 * @param $anex_salida opcional valor por defecto 1
 * @param $tpradic opcional valor por defecto 2, indica la bandeja de entrada (Entrada = 2)
 * 
 * Función que permite anexar documentos a los documentos radicados por el 
 * formulario web y guardarlos. Falta adicionarlo para servidor remoto.
 * 
 * El formulario para el input de file debe de de tener la etiqueta name="seleccionar"
 * 
 * El archivo se crea con el estandar de creación: Año de radicación y el numero
 *  de la dependencia y la carpeta docs.
 * En la carpeta bodega que esta en un nivel superior
 * Ej: raiz/bodega/2012/900/docs/
 * Los permisos son: rwxr-xr-x del usuario apache y grupo apache
 */
function anexar_radicado_web($numeroRadicado,$krdWeb,$dependenciaWeb,$radicado_rem,$anex_salida,$tpradic)
    {
    $default_krd_login = "FILTRADOR";
    $default_dependencia = 900;
    $ruta_raiz="..";
    if(!$numeroRadicado)return 1; //No se tiene un Numero de radicado para anexar
    
    $ADODB_COUNTRECS = false;   
    require_once("$ruta_raiz/include/db/ConnectionHandler.php");
    include_once("$ruta_raiz/class_control/anexo.php");
    include_once("$ruta_raiz/class_control/anex_tipo.php");
    include_once("$ruta_raiz/class_control/usuario.php");
    
    if (!$db)
        $db = new ConnectionHandler($ruta_raiz);
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    //$db->conn->debug = true;


//inicializando Variables por defecto

    $anex = & new Anexo($db);
    $anexTip = & new Anex_tipo($db);
    $usua= & new Usuario($db);
    
    //CONSULTANDO EL USUA_LOGIN PARA EL CAMPO ANEX_CREADOR DE LA TABLA ANEXOS
    
    
    if(!$dependenciaWeb)$dependenciaWeb = $_SESSION["depeRadicaFormularioWeb"];    
    if($krdWeb) {
        if($usua->usuarioDependecina($dependenciaWeb, $krdWeb)){
            $krdWeb = $usua->get_usua_login();
        }else{//no existe el usuario en esa dependencia
            $krdWeb=$default_krd_login;//POR DEFECTO EL USUARIO QUE RECIBE LOS DOCUMENTOS VIA WEB
            $dependenciaWeb=$default_dependencia;
        }        
    }else{
        if($usua->usuarioDependecina($dependenciaWeb, $_SESSION["usuaRecibeWeb"])){
            $krdWeb = $usua->get_usua_login();
        }else{//no existe el usuario de la variable global en esa dependencia
            $krdWeb=$default_krd_login;//POR DEFECTO EL USUARIO QUE RECIBE LOS DOCUMENTOS VIA WEB
            $dependenciaWeb=$default_dependencia;

        }     }
    
    if(!$radicado_rem)$radicado_rem = 1; //Este valor indica que es un ciudadano, ver la tabla
    //sgd_dir_direcciones el valor sgd_dir_tipo
    $auxsololect = "S";
    $descr = "Radicado Web";
    if(!$anex_salida)$anex_salida = 1;
    $sqlFechaHoy = $db->conn->OffsetDate(0, $db->conn->sysTimeStamp);
    if(!$tpradic)$tpradic = 2; //Importante: este indica a que bandeja de entrada debe de ir 
    //(Entrada=2, Salida=1, Memorando=3) ver la tabla sgd_trad_tiporad
    $anoRad = date("Y");//para indicar donde guardar el archivo


//inicializando variables de acuerdo a la BD
//Determinando el tipo del anexo ingresado
    $tipo_tmp = end(explode(".", $_FILES['seleccionar']['name']));
    $tipo = NULL;

    $isql = "select ANEX_TIPO_CODI, ANEX_TIPO_DESC, ANEX_TIPO_EXT " .
            "from anexos_tipo  where  ANEX_TIPO_EXT = '$tipo_tmp'";
    $rs = $db->conn->Execute($isql);
    while (!$rs->EOF) {
        $tipo = $rs->fields["anex_tipo_codi"];
        if(!$tipo)$tipo = $rs->fields["ANEX_TIPO_CODI"];
        $rs->MoveNext();
    }
    if (!$tipo) {
        //"la extension del archivo no existe en la BD";
    } else {//se crea el nuevo nombre para el archivo anexo
        $anexTip->anex_tipo_codigo($tipo);

        $ext = strtolower($anexTip->get_anex_tipo_ext());
        $auxnumero = str_pad($auxnumero, 5, "0", STR_PAD_LEFT);
        $archivo=trim($numeroRadicado."_".$auxnumero.".".$ext);
        $archivoconversion = trim("1") . trim(trim($numeroRadicado) . "_" . trim($auxnumero) . "." . trim($ext));
    }
//fin determinando el tipo del anexo
//Determinar el tamaño del archivo
    if ($_FILES['seleccionar']['size']) {
        $tamano = ($_FILES['seleccionar']['size'] / 1000);
    } else {
        $tamano = 0;
    }
// fin determinar el tamaño del archivo
//determinar el numero de anexos para este radicado, para el caso del formulario
// web este debe de ser siempre 1.
    $auxnumero = $anex->obtenerMaximoNumeroAnexo($numeroRadicado);
    do {
        $auxnumero+=1;
        $codigo = trim($numeroRadicado) . trim(str_pad($auxnumero, 5, "0", STR_PAD_LEFT));
    } while ($anex->existeAnexo($codigo));
//fin determinar numero de anexos
    
    // $radi = radicado padre
    // $radicado_rem = Codigo del tipo de remitente = sgd_dir_tipo
    // $codigo = ID UNICO DE LA TABLA
    // $tamano = tamano del archivo
    // $auxsololect = solo lectura?
    // $usua = usuario creador
    // $descr = Descripcion, el asunto
    // $auxnumero = Es codigo del consecutivo del anexo al radicado
    // Esta borrado?
    // $anex_salida = marca con 1 si es un radicado de salida

    $isql = "insert
                    into anexos
                        (sgd_rem_destino
                        ,anex_radi_nume
                        ,anex_codigo
                        ,anex_tipo
                        ,anex_tamano   
                        ,anex_solo_lect
                        ,anex_creador
                        ,anex_desc
                        ,anex_numero
                        ,anex_nomb_archivo   
                        ,anex_borrado
                        ,anex_salida 
                        ,sgd_dir_tipo
                        ,anex_depe_creador                        
                        ,anex_fech_anex
                        ".//,SGD_APLI_CODI
                        ",SGD_TRAD_CODIGO
                        ,SGD_EXP_NUMERO)
                     values (
                           $radicado_rem  
                         ,$numeroRadicado         
                         ,$codigo    
                         ,$tipo    
                         ,$tamano     
                         ,'$auxsololect'
                         ,'$krdWeb'     
                         ,'$descr' 
                         ,$auxnumero 
                         ,'$archivoconversion'
                         ,'N'         
                         ,$anex_salida
                         ,$radicado_rem
                         ,$dependenciaWeb                         
                         ,$sqlFechaHoy
                         ".//,$aplinteg    
                         ",$tpradic
                         ,'$expAnexo')";

    //echo $isql;
    
    $db->query($isql);
    include_once 'scriptCarpeta.php';
    bodegaCrearAnexos($anoRad, $dependenciaWeb, "docs");//se crean las carpetas sino existen
    //pra indicarse en el pdf generador (ver formulariopdf.php)
    $_SESSION['namefile_anexo_doc'] = substr(trim($archivo),0,4)."/".substr(trim($archivo),4,3)."/docs/".trim(strtolower($archivoconversion));
    // Where the file is going to be placed     
    $target_path = "$ruta_raiz/bodega/".substr(trim($archivo),0,4)."/".substr(trim($archivo),4,3)."/docs/";
    //$target_path = "$ruta_raiz/bodega/$anoRad/$dependenciaWeb/docs/";
    /* Add the original filename to our target path.  
      Result is "uploads/filename.extension" */
    if (move_uploaded_file($_FILES['seleccionar']['tmp_name'], $target_path.trim(strtolower($archivoconversion)))) {
        //echo "The file " . basename($_FILES['seleccionar']['name']) ." has been uploaded";
    } else {
        //echo "There was an error uploading the file, please try again!";
    }    
    return $target_path.trim(strtolower($archivoconversion));
}
/* //Pruebas
if($subir=="si"){
    $numeroRadicado = 20129000000122;
    $dependenciaWeb=900;
    $krdWeb=3;//el usuario web
    $namefile = $_FILES['seleccionar']['name'];
    $result = anexar_radicado_web($numeroRadicado, $krdWeb, $dependenciaWeb );
    if($result!=1){
        echo "Se guardo el archivo ".$result;
    }else{
        echo "No se guardo el archivo ".$result;
    }
    //echo "paso";
    
}else {
?>
<html>
    <body>
        <form  enctype="multipart/form-data" action="uploadArchivo.php?subir=si" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
            <input id="seleccionar" type="file" name="seleccionar" onclick="" />
            <input type="submit" value="Upload File" />            
        </form>
    </body>
    
</html>

<?php
}
/**/
?>
 