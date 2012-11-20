<?php
session_start();
/**
  * Se añadio compatibilidad con variables globales en Off
  * @autor Jairo Losada 2009-05
  * @Fundacion CorreLibre.org
  * @licencia GNU/GPL V 3
  */

foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

if($veronline=="si"){//AGREGADO POR METRO CALI S.A.
    
    $file = "../bodega".$rutaPdf;
    $filename = end(explode("/", $file));
    header('Content-type: application/pdf');
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Content-Length: ' . filesize($file));
    header('Accept-Ranges: bytes');
    @readfile($file);

}else if($veronline=="escritura"){//por seguridad
    require('barcode.php');
    include('funciones.php');

    //*********** AGREGADOR POR METRO CALI S.A.
    $ruta_raiz="..";
    require_once("../include/db/ConnectionHandler.php");
    include($ruta_raiz.'/class_control/Municipio.php');
    if(!$db) $db = new ConnectionHandler($ruta_raiz);
    $db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
    //$db->conn->debug=true;
    $municlass = new Municipio($db);
    $municlass->municipio_codigo($_SESSION['depto'],$_SESSION['muni'] );
    $muni_nomb = $municlass->get_muni_nomb();

    //VARIABLES PARA LOS CAMPOS
    $radi_codi_barras = $_SESSION['entidad']." Rad No. ".$_SESSION['radcom'];
    $radi_fecha_barras = "Fecha: ".date('d')."/".date('m')."/".date('Y')." ".date('h:i:s');
    $radi_ciufech_doc = $muni_nomb.", ".date('d')." de ".nombremes(date('m'))." de ".date('Y');
    $radi_asunt_doc = "Asunto : ".strtoupper($_SESSION['asunto']);
    $radi_anexo_doc = "Ver Anexo: ".$_SESSION['namefile_anexo_doc'];
    $radi_ciu_nombre= strtoupper(utf8_decode($_SESSION['nombre_remitente']))." ".strtoupper(utf8_decode($_SESSION['apellidos_remitente']));
    $radi_ciu_docu = "" .$_SESSION['cedula'];
    $radi_ciu_dir = utf8_decode("Dirección: ").$_SESSION['direccion_remitente'];
    $radi_ciu_tel = "Telefono: ".$_SESSION['telefono_remitente'];
    $radi_ciu_mail = "Email: ".$_SESSION['email'];
    $radi_url_consulta = "http://172.1.1.79/orfeo/consultaWeb/";
    $radi_consulta_doc = utf8_decode("Para consultar los radicados dirijase a ").$radi_url_consulta;
    //*****************************************


    $pdf=new PDF_Code39();
    $pdf->AddPage();
    $pdf->Code39(110,45,$_SESSION['radcom'],1,10);
    //$pdf->Image('../imagenes/PIEDEPAGINA_1.gif',30,275,160,19);
    $pdf->Image('../logoEntidadWeb.gif',40,10,50,50);
    $pdf->Text(110,63,$radi_codi_barras);
    $pdf->Text(110,67,$radi_fecha_barras);
    //$pdf->Text(110,71,strtoupper($_SESSION['sigla']));
    //$pdf->Text(110,75,$_SESSION['nit']);
    $pdf->Text(12,87,$radi_ciufech_doc);
    $pdf->Text(12,101,utf8_decode("Señores"));
    $pdf->SetFont('','B');
    $pdf->Text(12,105,$_SESSION['entidad']);
    $pdf->SetFont('','');
    $pdf->Text(12,109,"Cali");
    $pdf->Text(12,119,$radi_asunt_doc);
    $pdf->SetXY(11,125);
    $pdf->MultiCell(0,4,$_SESSION['desc'],0);
    if($_SESSION['namefile_anexo_doc'])$pdf->Text(12,212,$radi_anexo_doc);
    unset($_SESSION['namefile_anexo_doc']);// se quita la asignacion de la variable
    $pdf->Text(12,220,"Atentamente,");
    $pdf->SetFont('','B');
    $pdf->Text(12,246,$radi_ciu_nombre);
    $pdf->SetFont('','');
    $pdf->Text(12,250,$radi_ciu_docu);
    $pdf->Text(12,254,$radi_ciu_dir);
    if($_SESSION['telefono_remitente']!=0){
        $pdf->Text(12,258,$radi_ciu_tel);
        $pdf->Text(12,262,$radi_ciu_mail);
    }else{
        $pdf->Text(12,258,$radi_ciu_mail);
    }
    $pdf->SetFont('','B');
    $pdf->Text(12,268,$radi_consulta_doc);
    //guarda documento en un SERVIDOR
     // $pdf->Output("../bodega/tmp/".$_SESSION['radcom'].".pdf",'F');
    $pdf->Output("../bodega/$rutaPdf",'F');
    /*
    //envia el archivo a un SERVIDOR por FTP
    $archivo = "C:\\www\\data\\quejas\\".$_SESSION['radcom'].".pdf";
    $archivo_remoto = '/'.date('Y').'/440/'.$_SESSION['radcom'].'.pdf';

    // configurar la conexion basica
    $id_con = ftp_connect('192.127.28.10');

    // iniciar sesion con nombre de usuario y contrasenya
    //$resultado_login = ftp_login($id_con, 'orfeo','orfeo');
    $resultado_login = ftp_login($id_con, 'pruebas','pruebas');
    // cargar un archivo
    $texto=$_SESSION['radcom']." ";
    if (ftp_put($id_con, $archivo_remoto, $archivo, FTP_BINARY)) {
      $texto.="se ha cargado $archivo satisfactoriamente ";
    } else {
     $texto.="Hubo un problema durante la transferencia de $archivo ";
    }
    $texto.=date('Y-m-d h:i:s')."\n";
    // cerrar la conexion
    ftp_close($id_con);
    */

    //escribe el log
                    $nombre_archivo = '../bodega/log/quejas.txt';
                    $gestor = fopen($nombre_archivo, 'a');
                    fwrite($gestor, $texto);
                    fclose($gestor);

    // muestra el pdf
    //if($veronline)
    //$pdf->Output();

}
?>
