<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php'); 

$lineas = json_decode($_REQUEST["lineas"],true); 
$numero_recibo = $_REQUEST["numero_recibo"];
$cliente 	= $_REQUEST["cliente"];
$telefono	= $_REQUEST["telefono"];
$direccion	= $_REQUEST["direccion"];
$rif_cedula	= $_REQUEST["rif_cedula"];
$order_id = $_REQUEST["cid"];
$monto_divisa = $_REQUEST["monto_divisa"];

mysql_select_db($database_conexion, $conexion);
#$query_lista_order = "SELECT * FROM pos_order WHERE status_local='borrador'";
$query_lista_order = "SELECT * FROM pos_order WHERE pos_reference='$numero_recibo'";
$lista_order = mysql_query($query_lista_order, $conexion) or die(mysql_error());
$row_lista_order = mysql_fetch_assoc($lista_order);
$totalRows_lista_order = mysql_num_rows($lista_order);
$id_order=$row_lista_order['order_id'];

#mysql_select_db($database_conexion, $conexion);
#$query_elimina = "DELETE FROM pos_order WHERE status_local='borrador'";
#$elimina = mysql_query($query_elimina, $conexion) or die(mysql_error());

#mysql_select_db($database_conexion, $conexion);
#$query_elimina2 = "DELETE FROM pos_order_line WHERE order_id='$id_order'";
#$elimina2 = mysql_query($query_elimina2, $conexion) or die(mysql_error());

if($row_lista_order['fact_id']==""){
	mysql_select_db($database_conexion, $conexion);
	$query_lista_order = "INSERT INTO pos_order (pos_reference,cedula,cliente,telefono,direccion,order_id,monto_divisa) VALUES ('$numero_recibo','$rif_cedula','$cliente','$telefono','$direccion','$order_id','$monto_divisa')";
	$lista_order = mysql_query($query_lista_order, $conexion) or die(mysql_error());
	
	foreach($lineas as  $val) {
		$producto=$val['product'];
		//echo "<br>";
		$price_unit=$val['precio'];
		//echo "<br>";
		$cantidad=$val['cantidad'];
		//echo "<br>";
		$tipo_doc=$val['impuesto'];
		//echo "<br>";
		$descuento = $val['descuento'];

		if($tipo_doc=="0"){
			$valor_alicuota=0;
		}
		if($tipo_doc=="1"){
			$valor_alicuota=16;
		}
		if($tipo_doc=="2"){
			$valor_alicuota=8;
		}
		if($tipo_doc=="3"){
			$valor_alicuota=31;
		}
		
		mysql_select_db($database_conexion, $conexion);
		$query_lista_lineas_order = "INSERT INTO pos_order_line (producto,cantidad,price_unit,tipo_doc,order_id,valor_alicuota,pos_reference,descuento) VALUES ('$producto','$cantidad','$price_unit','$tipo_doc','$order_id','$valor_alicuota','$numero_recibo','$descuento')";
		$lista_lineas_order = mysql_query($query_lista_lineas_order, $conexion) or die(mysql_error());
	}
}
?>
<html>
<head>

<meta http-equiv="Refresh" content="3;url=index1.php">

</head>

<body marginheight="0" marginwidth="0">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#333333" height="38">&nbsp;<img src="imagenes/logo.png" width="87" height="auto"></td>
    <td bgcolor="#333333"><div align="right" class="texto_reloj2">Cerrar</div></td>
    <td bgcolor="#333333">&nbsp;</td>
  </tr>
  <tr><td colspan="3" align="center"><img src="imagenes/load3.gif"></td></tr>
  <tr><td colspan="3" align="center"><img src="imagenes/load4.gif" width="300" height="auto"></td></tr>
</table>
</body>
</html>

