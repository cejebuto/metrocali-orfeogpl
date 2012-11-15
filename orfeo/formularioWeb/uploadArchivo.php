<?php

/*
 * Creado por Metro Cali S.A.
 * Martes 16 de Octubre de 2012, 14:19
 * Script para el anexo de documentos para el formulario Web
 * 
 * @autor Edgar Moncada
 * Practicante de Metro Cali S.A
 * 
 * 
 */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

$ruta_raiz="..";
if($subir=="si"){
    $numeroRadicado=20129000000122;
    
   
include_once("$ruta_raiz/class_control/anexo.php");
include_once("$ruta_raiz/class_control/anex_tipo.php");

if (!$db)	$db = new ConnectionHandler($ruta_raiz);
    $db->conn->debug = true;
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);


$anex       = & new Anexo($db);
$anexTip    = & new Anex_tipo($db);



$krd         = $_SESSION["usuaRecibeWeb"];
$dependencia = $_SESSION["depeRadicaFormularioWeb"];

$auxsololect= "S";
$descr = "Radicado Web";
$anex_salida = 1;
$sqlFechaHoy= $db->conn->OffsetDate(0,$db->conn->sysTimeStamp);


//Determinando el tipo del anexo
$tipo_tmp = end(explode(".", $_FILES['seleccionar']['name']));
$tipo = NULL;


$isql = "select ANEX_TIPO_CODI, ANEX_TIPO_DESC, ANEX_TIPO_EXT ".
			"from anexos_tipo  where  ANEX_TIPO_EXT = '$tipo_tmp'";
	$rs=$db->conn->Execute($isql);
        while (!$rs->EOF){
            $tipo = $rs->fields["anex_tipo_codi"];
            $rs->MoveNext();
        }

if(!$tipo){
    //"Error la extension del archivo no existe en la BD";
}else{
    
    $anexTip->anex_tipo_codigo($tipo);

    $ext = strtolower($anexTip->get_anex_tipo_ext());
    $auxnumero = str_pad($auxnumero,5,"0",STR_PAD_LEFT);
    $archivoconversion=trim("1").trim(trim($numeroRadicado)."_".trim($auxnumero).".".trim($ext));
}


//fin determinando el tipo del anexo
 if($_FILES['seleccionar']['size']){
       $tamano = ($_FILES['seleccionar']['size']/1000);
     }else{
        $tamano = 0;
     }
     

$auxnumero=$anex->obtenerMaximoNumeroAnexo($numeroRadicado);
do{
    $auxnumero+=1;
    $codigo=trim($numeroRadicado).trim(str_pad($auxnumero,5,"0",STR_PAD_LEFT));
}while ($anex->existeAnexo($codigo));



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

$isql= "insert
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
                        ,SGD_APLI_CODI
                        ,SGD_TRAD_CODIGO
                        ,SGD_EXP_NUMERO)
                     values (
                           $radicado_rem  
                         ,$numeroRadicado         
                         ,$codigo    
                         ,$tipo    
                         ,$tamano     
                         ,'$auxsololect'
                         ,'$krd'     
                         ,'$descr' 
                         ,$auxnumero 
                         ,'$archivoconversion'
                         ,'N'         
                         ,$anex_salida
                         ,$radicado_rem
                         ,$dependencia                         
                         ,$sqlFechaHoy
                         ,$aplinteg    
                         ,$tpradic
                         ,'$expAnexo')";

echo $isql;


        // Where the file is going to be placed 
    $target_path = "";

    /* Add the original filename to our target path.  
    Result is "uploads/filename.extension" */
    $target_path = $target_path . basename( $_FILES['seleccionar']['name']); 
    
if(move_uploaded_file($_FILES['seleccionar']['tmp_name'], $target_path)) {
    echo "The file ".  basename( $_FILES['seleccionar']['name']). 
    " has been uploaded";
} else{
    echo "There was an error uploading the file, please try again!";
}
    
}else {

?>
<html>
    <body>
        <form  enctype="multipart/form-data" action="uploadArchivo.php?subir=si" method="POST">
            <input type="hidden" name="MAX_FILE_SIZE" value="100000" />
            <input id="seleccionar" type="file" name="seleccionar" onclick=" $var = document.getElementById('seleccionar').value; alert($var);" />
            <input type="submit" value="Upload File" />
            
        </form>
        

        
    </body>
    
</html>

<?php
}
?>