<html>
<head>
<title>Creacion grafica de flujos</title>
<meta HTTP-EQUIV="expires" CONTENT="0">
</head>
<body>
    <? $session = $PHPSESSID; // Agregado por Metrocali S.A. ?>
<APPLET  
ARCHIVE="jgraph.jar"
CODE=co.gov.superservicios.orfeo.flujos.java.editorFlujos.class
width=800 height=1000>
<param 	name="usuario" value="<?=$krd?>" />
<param 	name="ses" value="<?=$session?>" />
</APPLET>
</body>
</html>
