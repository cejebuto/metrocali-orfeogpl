<?php
/** CONSUTLA 001 
	* Estadiscas por medio de recepcion Entrada
	* @autor JAIRO H LOSADA - SSPD
	* @version ORFEO 3.1
	* 
	*/
$coltp3Esp = '"'.$tip3Nombre[3][2].'"';
if(!$orno) $orno=2;
$tmp_substr = $db->conn->substr;
 /**
   * $db-driver Variable que trae el driver seleccionado en la conexion
   * @var string
   * @access public
   */
 /**
   * $fecha_ini Variable que trae la fecha de Inicio Seleccionada  viene en formato Y-m-d
   * @var string
   * @access public
   */
/**
   * $fecha_fin Variable que trae la fecha de Fin Seleccionada
   * @var string
   * @access public
   */
/**
   * $mrecCodi Variable que trae el medio de recepcion por el cual va a sacar el detalle de la Consulta.
   * @var string
   * @access public
   */
switch($db->driver)
{
	case 'mssql':
	case 'postgresql':	
	case 'postgres':	
	{	if($tipoDocumento=='9999')
		{	$queryE = "SELECT b.USUA_NOMB as USUARIO, count(*) as RADICADOS, MIN(USUA_CODI) as HID_COD_USUARIO
	, MIN(depe_codi) as HID_DEPE_USUA 
	FROM RADICADO r 
	INNER JOIN USUARIO b ON r.radi_usua_radi=b.usua_CODI AND $tmp_substr($radi_nume_radi,5,3)=cast(b.depe_codi as varchar) 
	WHERE ".$db->conn->SQLDate('Y/m/d', 'r.radi_fech_radi')." BETWEEN '$fecha_ini' AND '$fecha_fin' 
	$whereDependencia $whereActivos $whereTipoRadicado 
					GROUP BY b.USUA_NOMB ORDER BY $orno $ascdesc";
		}
		else
		{	$queryE = "SELECT b.USUA_NOMB as USUARIO, t.SGD_TPR_DESCRIP as TIPO_DOCUMENTO, count(*) as RADICADOS,
						MIN(USUA_CODI) as HID_COD_USUARIO, MIN(SGD_TPR_CODIGO) as HID_TPR_CODIGO, MIN(depe_codi) as HID_DEPE_USUA
					FROM RADICADO r 
						INNER JOIN USUARIO b ON r.RADI_USUA_RADI = b.USUA_CODI AND $tmp_substr($radi_nume_radi, 5, 3) = cast(b.DEPE_CODI as varchar) 
						LEFT OUTER JOIN SGD_TPR_TPDCUMENTO t ON r.TDOC_CODI = t.SGD_TPR_CODIGO
					WHERE ".$db->conn->SQLDate('Y/m/d', 'r.radi_fech_radi')." BETWEEN '$fecha_ini' AND '$fecha_fin' 
						$whereDependencia $whereActivos $whereTipoRadicado 
					GROUP BY b.USUA_NOMB,t.SGD_TPR_DESCRIP ORDER BY $orno $ascdesc";		
		}
 		/** CONSULTA PARA VER DETALLES 
	 	*/
		$condicionDep = ($dependencia_busq==99999) ? " AND b.depe_codi is not null " : "AND b.depe_codi = $dependencia_busq ";
		$condicionE = " AND b.USUA_CODI=$codUs $condicionDep ";
		$queryEDetalle = "SELECT $radi_nume_radi as RADICADO
	,r.RADI_FECH_RADI as FECHA_RADICADO
	,t.SGD_TPR_DESCRIP as TIPO_DE_DOCUMENTO
	,r.RA_ASUN as ASUNTO 
	,r.RADI_DESC_ANEX 
	,r.RADI_NUME_HOJA 
	,b.usua_nomb as Usuario
	,r.RADI_PATH as HID_RADI_PATH {$seguridad}
	, dir.SGD_DIR_NOMREMDES as REMITENTE
	,df.DEPE_NOMB as DEPE_NOMB
	,da.DEPE_NOMB as DEPE_NOMB_ACTUAL
	,r.RADI_USU_ANTE
	,ua.usua_nomb AS USUA_NOMB_ACTUAL
FROM dependencia df,dependencia da,USUARIO ua, RADICADO r
	INNER JOIN USUARIO b ON r.radi_usua_radi=b.usua_CODI AND $tmp_substr($radi_nume_radi,5,3)=cast(b.depe_codi as varchar) 
	LEFT OUTER JOIN SGD_TPR_TPDCUMENTO t ON r.tdoc_codi=t.SGD_TPR_CODIGO 
	LEFT OUTER JOIN SGD_DIR_DRECCIONES dir ON r.radi_nume_radi = dir.radi_nume_radi	
	WHERE 
	r.radi_depe_actu=da.depe_codi AND
	r.radi_depe_actu=ua.depe_codi AND
	r.radi_usua_actu=ua.usua_codi AND
	r.RADI_DEPE_RADI=df.DEPE_CODI AND	
	".$db->conn->SQLDate('Y/m/d', 'r.radi_fech_radi')." BETWEEN '$fecha_ini' AND '$fecha_fin' $whereTipoRadicado ";
					$orderE = "	ORDER BY $orno $ascdesc";
			 /** CONSULTA PARA VER TODOS LOS DETALLES 
			 */ 
		
			$queryETodosDetalle = $queryEDetalle . $condicionDep . $orderE;
			$queryEDetalle .= $condicionE . $orderE;
	// $db->conn->debug = true;
	}break;
	case 'oracle':
	case 'oci8':
	case 'oci805':
	case 'ocipo':
	{
		if($tipoDocumento=='9999')
		{
			$queryE = 
			"SELECT b.USUA_NOMB USUARIO, 
				count(*) RADICADOS, 
				MIN(USUA_CODI) HID_COD_USUARIO, 
				MIN(depe_codi) HID_DEPE_USUA
			FROM RADICADO r, USUARIO b, sgd_dir_drecciones dir
			WHERE 
				r.radi_nume_radi=dir.radi_nume_radi and
				r.radi_usua_radi=b.usua_CODI 
				AND substr(r.radi_nume_radi,5,3)=b.depe_codi
				$whereDependencia
				AND TO_CHAR(r.radi_fech_radi,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin' 
				$whereActivos
			$whereTipoRadicado 
			GROUP BY b.USUA_NOMB
			ORDER BY $orno $ascdesc";
		}
		else
		{
			$queryE = "
		    SELECT b.USUA_NOMB USUARIO
				, t.SGD_TPR_DESCRIP TIPO_DOCUMENTO
				, count(*) RADICADOS
				, MIN(USUA_CODI) HID_COD_USUARIO
				, MIN(SGD_TPR_CODIGO) HID_TPR_CODIGO
				, MIN(depe_codi) HID_DEPE_USUA
			FROM RADICADO r, USUARIO b, SGD_TPR_TPDCUMENTO t
			WHERE 
				r.radi_usua_radi=b.usua_CODI 
				AND r.tdoc_codi=t.SGD_TPR_CODIGO (+)
				AND substr(r.radi_nume_radi,5,3)=b.depe_codi
				$whereDependencia 
				AND TO_CHAR(r.radi_fech_radi,'yyyy/mm/dd') BETWEEN '$fecha_ini'  AND '$fecha_fin' 
				$whereActivos
			$whereTipoRadicado 
			GROUP BY b.USUA_NOMB,t.SGD_TPR_DESCRIP
			ORDER BY $orno $ascdesc";
		}
 		/** CONSULTA PARA VER DETALLES 
	 	*/
		$condicionDep = ($dependencia_busq==99999) ? "" : "AND depe_codi = $dependencia_busq ";
		$condicionE = " AND b.USUA_CODI=$codUs $condicionDep ";
		$queryEDetalle = "SELECT DISTINCT r.RADI_NUME_RADI RADICADO
			,r.RADI_FECH_RADI FECHA_RADICADO
			,t.SGD_TPR_DESCRIP 	TIPO_DE_DOCUMENTO
			,r.RA_ASUN ASUNTO
			,r.RADI_DESC_ANEX ANEXOS
			,r.RADI_NUME_HOJA N_HOJAS
			,b.usua_nomb USUARIO
			,r.RADI_PATH HID_RADI_PATH
			,dir.sgd_dir_nomremdes REMITENTE 
			FROM RADICADO r, 
				USUARIO b, 
				SGD_TPR_TPDCUMENTO t,
				sgd_dir_drecciones dir
		WHERE 
			r.radi_nume_radi = dir.radi_nume_radi 
			and r.radi_usua_radi=b.usua_CODI 
			AND r.tdoc_codi=t.SGD_TPR_CODIGO 
			AND substr(r.radi_nume_radi,5,3)=b.depe_codi
			AND TO_CHAR(r.radi_fech_radi,'yyyy/mm/dd') BETWEEN '$fecha_ini' AND '$fecha_fin'
		$whereTipoRadicado";
		$orderE = "	ORDER BY $orno $ascdesc";			

		/** CONSULTA PARA VER TODOS LOS DETALLES 
	 	*/ 
		$queryETodosDetalle = $queryEDetalle . $condicionDep . $orderE;
		$queryEDetalle .= $condicionE . $orderE;
	}break;
}

if(isset($_GET['genDetalle'])==1)
	$titulos=array("#","1#RADICADO","2#FECHA RADICADO","3#TIPO DOCUMENTO","4#ASUNTO","5#NO HOJAS","6#USUARIO","7#REMITENTE","8#DEPENDENCIA_INICIAL","9#DEPENDENCIA_ACTUAL","10#USUARIO ACTUAL","11#USUARIO ANTERIOR");
else 		
	$titulos=array("#","1#Usuario","2#Radicados");
		
function pintarEstadistica($fila,$indice,$numColumna)
{
	global $ruta_raiz,$_POST,$_GET,$krd;
	$salida="";
	switch ($numColumna)
	{
	case  0:
		$salida=$indice;
		break;
	case 1:	
		$salida=$fila['USUARIO'];
		break;
	case 2:
	$datosEnvioDetalle="tipoEstadistica=".$_GET['tipoEstadistica']."&amp;genDetalle=1&amp;usua_doc=".urlencode($fila['HID_USUA_DOC'])."&amp;dependencia_busq=".$_GET['dependencia_busq']."&amp;fecha_ini=".$_GET['fecha_ini']."&amp;fecha_fin=".$_GET['fecha_fin']."&amp;tipoRadicado=".$_GET['tipoRadicado']."&amp;tipoDocumento=".$_GET['tipoDocumento']."&amp;codUs=".$fila['HID_COD_USUARIO'];
	$datosEnvioDetalle=(isset($_GET['usActivos']))?$datosEnvioDetalle."&amp;usActivos=".$_GET['usActivos']:$datosEnvioDetalle;
	$salida="<a href=\"genEstadistica.php?{$datosEnvioDetalle}&amp;krd={$krd}\"  target=\"detallesSec\" >".$fila['RADICADOS']."</a>";
	break;
	default: $salida=false;
	break;
}
	return $salida;
}

function pintarEstadisticaDetalle($fila,$indice,$numColumna)
{
	global $ruta_raiz,$encabezado,$krd;
	$verImg=($fila['SGD_SPUB_CODIGO']==1)?($fila['USUARIO']!=$_SESSION['usua_nomb']?false:true):($fila['USUA_NIVEL']>$_SESSION['nivelus']?false:true);
    $numRadicado=$fila['RADICADO'];	
	switch ($numColumna)
	{
	case 0:
		$salida=$indice;
		break;
	case 1:
		if($fila['HID_RADI_PATH'] && $verImg)
			$salida="<center><a href=\"{$ruta_raiz}bodega".$fila['HID_RADI_PATH']."\">".$fila['RADICADO']."</a></center>";
		else 
			$salida="<center class=\"leidos\">{$numRadicado}</center>";	
		break;
	case 2:
		if($verImg)
			$salida="<a class=\"vinculos\" href=\"{$ruta_raiz}verradicado.php?verrad=".$fila['RADICADO']."&amp;".session_name()."=".session_id()."&amp;krd=".$_GET['krd']."&amp;carpeta=8&amp;nomcarpeta=Busquedas&amp;tipo_carp=0 \" >".$fila['FECHA_RADICADO']."</a>";
		else 
			$salida="<a class=\"vinculos\" href=\"#\" onclick=\"alert(\"ud no tiene permisos para ver el radicado\");\">".$fila['FECHA_RADICADO']."</a>";
		break;
	case 3:
		$salida="<center class=\"leidos\">".$fila['TIPO_DE_DOCUMENTO']."</center>";		
		break;
	case 4:
		$salida="<center class=\"leidos\">".$fila['ASUNTO']."</center>";
		break;
	case 5:
		$salida="<center class=\"leidos\">".$fila['N_HOJAS']."</center>";			
		break;	
	case 6:
		$salida="<center class=\"leidos\">".$fila['USUARIO']."</center>";			
		break;	
	case 7:
		$salida="<center class=\"leidos\">".$fila['REMITENTE']."</center>";			
		break;
	case 8:
		$salida="<center class=\"leidos\">".$fila['DEPE_NOMB']."</center>";			
		break;		
	case 9:
		$salida="<center class=\"leidos\">".$fila['DEPE_NOMB_ACTUAL']."</center>";			
		break;
	case 10:
		$salida="<center class=\"leidos\">".$fila['USUA_NOMB_ACTUAL']."</center>";			
		break;
	case 11:
		$salida="<center class=\"leidos\">".$fila['RADI_USU_ANTE']."</center>";			
		break;
	}
	return $salida;
}
?>
