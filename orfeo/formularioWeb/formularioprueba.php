<?
session_start();
/**
  * Modulo de Formularios Web para atencion a Ciudadanos.
  * @autor Carlos Barrero   carlosabc81@gmail.com SuperSolidaria
  * @fecha 2009/05
  * 
  */
foreach ($_GET as $key => $valor)   ${$key} = $valor;
foreach ($_POST as $key => $valor)   ${$key} = $valor;

define('ADODB_ASSOC_CASE', 1);

if($_GET["orderNo"]) $orderNo=$_GET["orderNo"];
if($_GET["orderTipo"]) $orderTipo=$_GET["orderTipo"];
if($_GET["busqRadicados"]) $gen_lisDefi=$_GET["busqRadicados"];

// $depeRadicaFormularioWeb = 900;  // Es radicado en la Dependencia 900
// $usuaRecibeWeb = 1; // Usuario que Recibe los Documentos Web
// $secRadicaFormularioWeb = 900;

$ruta_raiz = "..";
$ADODB_COUNTRECS = false;
require_once("$ruta_raiz/include/db/ConnectionHandler.php");
include "../config.php";
$_SESSION["depeRadicaFormularioWeb"]=$depeRadicaFormularioWeb;  // Es radicado en la Dependencia 900
$_SESSION["usuaRecibeWeb"]=$usuaRecibeWeb; // Usuario que Recibe los Documentos Web
$_SESSION["secRadicaFormularioWeb"]=$secRadicaFormularioWeb; // Osea que usa la Secuencia sec_tp2_900
$inicio = true; //variable añadida por Metro Cali S.A. para colocar predeterminadamente Depto y Muni. (Valle y Cali)
$db = new ConnectionHandler($ruta_raiz);
$db->conn->SetFetchMode(ADODB_FETCH_ASSOC);
//$db->conn->debug = true;

include('./funciones.php');
include('./formulario_sql.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>

        <title>
            Formulario Solicitudes, Quejas y Reclamos
        </title>

        <!-- Meta Tags -->
        <meta http-equiv="Content-Type" content="text/html; charset= iso-8859-1" />

        <!-- CSS -->
        <link href="css/structureformulario.css" rel="stylesheet">
        <link href="css/styleformulario.css" rel="stylesheet">
        <link href="css/themeformulario.css" rel="stylesheet">

        <!-- JavaScript -->
        <script src="scripts/wufooformulario.js"></script>
        <!-- prototype -->
        <script type="text/javascript" src="prototype.js"></script>
        <!--funciones-->
        <script type="text/javascript" src="ajax.js"></script>

        <!--[if lt IE 10]>
        <script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
    </head>

    <body id="public">
        <div id="container" style="background-image:url(../imagenes/a.gif);">

        
            

            <form id="form1" class="wufoo leftLabel page" autocomplete="off" 
                  enctype="multipart/form-data" method="GET" action="formulariotx.php" name="quejas">

                <header id="header" class="info">
                    <center> 
                        <img src="images/orfeologo.png" height="60" width="200" >
                            <br>
                        <a href="http://www.metrocali.gov.co">
                            <img src='../logoEntidad.gif' height="150" width="190">
                        </a>
                    </center>
                    <h4><?=$db->entidad_largo?></h4>
                    <h2>Formulario Solicitudes, Quejas y Reclamos</h2>
                    <div></div>
                </header>

                <ul>

                    <li id="foli5" class="notranslate      ">
                        <label class="desc" id="title5" for="Field5">
                            Nombre del Remitente
                            <span id="req_5" class="req">*</span>
                        </label>
                        <span>
                            <input id="Field5" name="nombre_remitente" type="text" class="field text fn" value="" size="8" tabindex="1" required />
                            <label for="Field5">Nombre</label>
                        </span>
                        <span>
                            <input id="Field6" name="apellidos_remitente" type="text" class="field text ln" value="" size="14" tabindex="2" required />
                            <label for="Field6">Apellidos</label>
                        </span>
                        <p class="instruct" id="instruct5"><small>Ingrese su nombre</small></p>
                    </li>
                    <li id="foli7" class="notranslate       ">
                        <label class="desc" id="title7" for="Field7">
                            Tipo de Documento
                            <span id="req_7" class="req">*</span>
                        </label>
                        <div>
                            <select id="Field7" name="tipo_doc_id" class="field select medium" tabindex="3" > 
                                <?=$list_tipo_doc ?>
                            </select>
                        </div>
                        <p class="instruct" id="instruct7"><small>Elija su tipo de documento de identificaci&oacute;n (ej: C.C., Nit, entre otros)</small></p>
                    </li>
                    <li id="foli10" class="notranslate      ">
                        <label class="desc " id="title10" for="Field10">
                            N&uacute;mero de Documento
                            <span id="req_10" class="req">*</span>
                        </label>
                        <div>
                            <input id="Field10" name="cedula" type="text" class="field text nospin medium"  value="" maxlength="20" tabindex="4" onkeyup="validateRange(10, 'digit');" required />
                        </div>
                        <p class="instruct " id="instruct10"><small>Ingrese su n&uacute;mero de identificaci&oacute;n</small></p>
                    </li><li id="foli8" class="       ">
                        <label class="desc" id="title8" for="Field8">
                            Departamento
                            <span id="req_8" class="req">*</span>
                        </label>
                        <div>
                            <select id="label" name="depto" class="field select medium" tabindex="5" onchange="trae_municipio()"> 
                                <?=$depto ?>
                            </select>
                        </div>
                        <p class="instruct" id="instruct8"><small>Seleccione el Departamento de su recidencia</small></p>
                    </li>
                    <li id="foli11" class="       ">
                        <label class="desc" id="title11" for="Field11">
                            Municipio                            
                            <img src="images/loading_animated2.gif" width="48" height="48" style="display:none" id="loader1"/></label>
                        </label>
                        <div id="div-contenidos">
                            <!-- <select name='muni' class='field select medium' tabindex='6'> -->
                                <?=$muni ?>
                            <!--</select> -->
                        </div>
                        <p class="instruct" id="instruct11"><small>Seleccione el municipio al que pertenece</small></p>
                    </li>
                    <li id="foli18" class="notranslate      ">
                        <label class="desc" id="title18" for="Field18">
                            Direcci&oacute;n
                            <span id="req_18" class="req">*</span>
                        </label>
                        <div>
                            <input id="Field18" name="direccion_remitente" type="text" class="field text large" value="" maxlength="255" tabindex="7" onkeyup="" required />
                        </div>
                        <p class="instruct" id="instruct18"><small>Ingrese la direcci&oacute;n de recidencia</small></p>
                    </li><li id="foli19" class="notranslate      ">
                        <label class="desc" id="title19" for="Field19">
                            N&uacute;mero de telefono
                        </label>
                        <div>
                            <input id="Field19" class="field text medium" name="telefono_remitente" tabindex="8" type="tel" maxlength="255" value="" /> 
                        </div>
                        <p class="instruct" id="instruct19"><small>Ingrese su n&uacute;mero telefonico</small></p>
                    </li><li id="foli20" class="notranslate      ">
                        <label class="desc" id="title20" for="Field20">
                            Correo electr&oacute;nico
                        </label>
                        <div>
                            <input id="Field20" name="email" type="email" spellcheck="false" class="field text medium" value="" maxlength="255" tabindex="9" /> 
                        </div>
                        <p class="instruct" id="instruct20"><small>Ingrese su direcci&oacute;n de correo electronico (ej: juanito@mail.com)</small></p>
                    </li><li id="foli21" class="notranslate section      ">
                        <section>
                            <h3 id="title21">
                            </h3>
                        </section>
                    </li><li id="foli22" class="notranslate       ">
                        <label class="desc" id="title22" for="Field22">
                            Tipo de solicitud
                            <span id="req_22" class="req">*</span>
                        </label>
                        <div>
                            <select id="Field22" name="tipo" class="field select medium" tabindex="10" required> 
                                <option value="" > Seleccionar </option>
                                <?= $tipo ?>
                            </select>
                        </div>
                        <p class="instruct" id="instruct22"><small>Ingrese el tipo de su solicitud para que sea mas f&aacute;cil de identificar</small></p>
                    </li>
                    <li id="foli23" 		class="   ">
                      <label class="desc" id="title23" for="label6">
                          Asunto
                          <span id="req_23" class="req">*</span>
                      </label>                        
                      <div>
                        <input id="label6" name="asunto" type="text" class="field text large" value="" maxlength="255" tabindex="11" required/>
                      </div>
                      <p class="instruct" id="instruct20"><small>Ingrese el asunto de su solicitud</small></p>
                    </li>
                    <li id="foli24" class="    ">
                        <label class="desc" id="title24" for="Field111">
                            Descripci&oacute;n
                            <span id="req_24" class="req">*</span>
                        </label>
                        <div>
                            <textarea id="desc" 
                                name="desc" 
                                class="field textarea small" 
                                rows="10" cols="50"
                                tabindex="12"
                                required
                                 ></textarea>
                        </div>
                        <p class="instruct" id="instruct20"><small>Ingrese una descripci&oacute;n de la solicitud que esta realizando</small></p>
                    </li>
                    <li class="buttons ">
                        <div>

                            <input id="saveForm" name="saveForm" class="btTxt submit" 
                                   type="submit" value="Enviar"
                                   />
                            <input type="button" name="cerrar" value="Cerrar" onclick="window.location = 'http://www.metrocali.gov.co' " />
                        </div>
                    </li>
                    
                </ul>
            </form> 

        </div><!--container-->

        <a class="powertiny" href="http://wufoo.com/form-builder/" title="Powered by Wufoo"
           style="display:block !important;visibility:visible !important;text-indent:0 !important;position:relative !important;height:auto !important;width:95px !important;overflow:visible !important;text-decoration:none;cursor:pointer !important;margin:0 auto !important">
            <span style="background:url(./images/powerlogo.png) no-repeat center 7px; margin:0 auto;display:inline-block !important;visibility:visible !important;text-indent:-9000px !important;position:static !important;overflow: auto !important;width:62px !important;height:30px !important">Wufoo</span>
            <b style="display:block !important;visibility:visible !important;text-indent:0 !important;position:static !important;height:auto !important;width:auto !important;overflow: auto !important;font-weight:normal;font-size:9px;color:#777;padding:0 0 0 3px;">Designed</b>
        </a>
    </body>
</html>