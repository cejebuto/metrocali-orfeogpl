<?php 
session_start();

    $ruta_raiz = "."; 
    if (!$_SESSION['dependencia'])
        header ("Location: $ruta_raiz/cerrar_session.php");
    
    $krd            = $_SESSION["krd"];
    $dependencia    = $_SESSION["dependencia"];
    $usua_doc       = $_SESSION["usua_doc"];
    $codusuario     = $_SESSION["codusuario"];
    $tip3Nombre     = $_SESSION["tip3Nombre"];
    $tip3desc       = $_SESSION["tip3desc"];
    $tip3img        = $_SESSION["tip3img"];
    $ESTILOS_PATH   = $_SESSION["ESTILOS_PATH"];
    $fechah         = date("Ymdhms");
    $ruta_raiz      = ".";
    
    
    // Mostrar el numero de radicados actules en el carrito
    // de radicados
    
    $archivo = "sessR$krd";
    $fila = "$ruta_raiz/bodega/tmp/$archivo";
    
    if(file_exists($fila)){
    
        $fp             = fopen($fila, "r");    
        $contenido      = fread($fp, filesize($fila));            
        fclose($fp);
        
        //Extraemos el contenido del archivo en un arreglo de
        //solo radicados                                                
        $arrayData      = preg_split('/[\D]+/',$contenido);                
        
        //Filtrar solo datos numericos
        $arrayData      = array_filter($arrayData, "is_numeric");        
        
        //Dato a mostrar en el div
        $radActuales    = count($arrayData);
    }else{
        $radActuales = 0;
    }

?>
    <html>
        <head>
            
            <!--
            Modificado por Metro Cali S.A.            
            <link rel="stylesheet" href="<?/*=$ruta_raiz."/estilos/".$_SESSION["ESTILOS_PATH"]*/?>/orfeo.css">            
            El valor de $_SESSION["ESTILOS_PATH"] es /estilos/orfeo38/     
            Ver config.php donde estan las variables de configuracion del sistema.
            -->
            <link rel="stylesheet" href="<?=$ruta_raiz."".$_SESSION["ESTILOS_PATH"]?>orfeo.css">
            <!-- Fin modificado por Metro Cali S.A. -->
            
            <!--Se agregan localmente para no dañar el resto de pagians
            se arregla formato mediante el sisguiente css -->
            <style type="text/css">
            body {
                margin-bottom:0;
                margin-left:0;
                margin-right:0;
                margin-top:0;
                padding-bottom:0;
                padding-left:0;
                padding-right:0;
                padding-top:0; 
            }
            </style>
            
            
            
            <!-- xINICIO Script que crea la sesion y la cierra para el carro de compras-->
            <script language="javascript">                
                function returnKrdF_top(){
                    return '<?=$krd?>';
                };
    
                function nueva(){
                    open('plantillas.php?<?=session_name()."=".session_id()?>', 'Sizewindow', 'width=800,height=600,scrollbars=yes,toolbar=no') 
                } 

            </script>
            <script type="text/javascript" src="<?=$ruta_raiz?>/js/jquery-1.4.2.min.js"></script>            
            <script type="text/javascript" src="<?=$ruta_raiz?>/js/ajaxSessionRads.js"></script>                      
            <!-- FIN    Script que crea la sesion y la cierra para el carro de compras-->
            
            
            <script language="JavaScript" type="text/JavaScript">

                function cerrar_session() {
		    if (confirm('Seguro de cerrar sesion ?')){
                        <?$fechah = date("Ymdhms"); ?>
                        url="login.php?adios=chao";document.form_cerrar.submit();
	                url = 'login.php?<?= session_name()."=".session_id()."&fechah=$fechah"?>';
			window.location.href=url;
		    }
		}
            </script>
            <script language="JavaScript" type="text/JavaScript">                
                
                function MM_swapImgRestore(){
                    var i,x,a=document.MM_sr; for(i=0;a&&i
                    <a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
                }
                
                function MM_preloadImages(){
                    var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
                    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i
                    <a.length; i++)
                    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
                }
                
                function MM_findObj(n, d){
                    var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
                    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
                    if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i
                    <d.forms.length;i++) x=d.forms[i][n];
                    for(i=0;!x&&d.layers&&i
                    <d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
                    if(!x && d.getElementById) x=d.getElementById(n); return x;
                }
                
                function MM_swapImage(){
                    var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i
                    <(a.length-2);i+=3)
                    if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
                }
            </script>

        </head>
        <body topmargin="0" leftmargin="0" onLoad="MM_preloadImages('im&#225;genes/cabezote_r1_c4.gif');MM_preloadImages('im&#225;genes/cabezote_r1_c5.gif');MM_preloadImages('im&#225;genes/cabezote_r1_c6.gif');MM_preloadImages('im&#225;genes/cabezote_r1_c7.gif')">
            <form name="form_cerrar" action="cerrar_session.php?<?=session_name()."=".session_id()."&fechah=$fechah"?>" target="_parent method=post">
	            <input type='hidden' name='<?=session_name()?>' value='<?=session_id()?>'> 
                <table width="100%" height="76" border="0" cellpadding="0" cellspacing="0" class="eFrameTop">
                    <tr>
                        <td width="206">
                            <img name="cabezote_r1_c1" src="imagenes/logo.gif" height="76px" border="0" alt="">
                        </td>
                        <td>
                            <img name="cabezote_r1_c2" src="imagenes/cabezote_r1_c2.gif" width="100%" height="76" border="0" alt="">
                        </td>
                        <td width="61" height="76px" background="imagenes/carritoD2.gif" border="0">
                                <div id="carrito" >
                                        <div id="numeroCarrito">
                                            <?=$radActuales?>
                                        </div>
                                        <div id="activar" name="0_carrito">
                                            <img src='imagenes/carritoOn.gif'  border=0 title="Activar Carro de Documentos" alt="Activar Carro de Documentos">
                                        </div>
                                        <div id="inactivar" name="1_carrito">
                                            <img src='imagenes/carritoOff.gif' border=0 title="Inactivar Carro de Documentos" alt="Inactivar Carro de Documentos" >
                                        </div>
                                        <a href="./cuerpoCarrito.php?<?=session_name()."=".session_id()?>" target="mainFrame">
                                            <div id="carroRadi"></div>           
                                        </a>                     
                                </div>                                                    
                        </td>
                        <td width="61" height="76px">
                            <a href="javascript:nueva()"><img src="imagenes/plant.gif" width="61px" height="76px" border="0"/></a>
                        </td>
                        <td width="62">
                            <a href="./Manuales/ayudaorfeo/content.htm" target="Ayuda_Orfeo"><img src="./imagenes/ayuda.gif" name="Image8" width="62" height="76" border="0" title="MANUAL ORFEO" ALT='Ayuda'></a>
                        </td>
                        <td width="61">
                            <a href="mod_datos.php?<?=session_name()."=".session_id()."&fechah=$fechah&krd=$krd&info=false"?>" target=mainFrame><img src="imagenes/info.gif" name="Image9" width="61"   height="76" border="0"></a>
                        </td>
                        <td width="61">
                            <a href="menu/creditos.php?<?=session_name()."=".session_id()."&fechah=$fechah&krd=$krd&info=false"?>" target=mainFrame><img src="imagenes/creditos.gif" name="Image12" width="61"   height="76" border="0"></a>
                        </td>
                        <td width="63">
                            <?php
                            if ($autentica_por_LDAP == 0) {
                                
                            ?>
                            <a href='contraxx.php?<?=session_name()."=".session_id()."&fechah=$fechah"?>' target=mainFrame><img src="imagenes/contrasena.gif" name="Image10" width="63" height="76" border="0"></a>
                            <?php
                            }
                            else if ($autentica_por_LDAP == 1) {
                                
                            ?>
                            <a href=""><img src="imagenes/cabezote_r1_c2.gif" name="Image10" width="63" height="76" border="0"></a>
                            <?php
                            }
                            ?>
                        </td>
                        <td width="66">
                            <a href="./estadisticas/vistaFormConsulta.php?<?=session_name()."=".trim(session_id())."&fechah=$fechah"?>" target=mainFrame><img src="imagenes/estadistic.gif" name="Image11" width="66"   height="76" border="0"></a>
                        </td>
                        <td width="54">
                            <a href="cerrar_session.php?<?= session_name()."=".session_id()?>"> <img name="cabezote_r1_c8" src="imagenes/salir.gif" width="54" height="76" border="0" alt=""></a>
                        </td>
                        <td width="54">
                            <a href="./soporte/index.php?<?=session_name()."=".session_id()."&krd=$krd"?>" target=mainFrame>
                            <img src="imagenes/soporte.gif" width="54" height="76" border="0" alt=""></a>
                        </td>
                    </tr>
                </table>
            </form>
        </body>
    </html>
