<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php'); 
#$ip=$host.':'.$puerto_apli;
#$ip=$host;
$accion=$_REQUEST["accion"];
$id_order=$_REQUEST["id_order"];
?>
<?php
$barra[0]="#FFFFFF";
$barra[1]="#E5E5E5";
if($accion==2){
include("generar_prn.php");
exec('c:\WINDOWS\system32\cmd.exe /c START C:\wamp\www\fiscal_13\index.bat');
echo '<script> alert("Generando Impresion de factura con exito.");</script>';	
exec('c:\WINDOWS\system32\cmd.exe /c START C:\wamp\www\fiscal_13\actualiza_fact.bat');
mysql_select_db($database_conexion, $conexion);
$query_actualiza = "UPDATE pos_order SET status_local='done' WHERE order_id = '$id_order'";
$actualiza = mysql_query($query_actualiza, $conexion) or die(mysql_error());
}


mysql_select_db($database_conexion, $conexion);
//$query_lista_order = "SELECT * FROM pos_order ORDER BY order_id DESC LIMIT 0,1";
$query_lista_order = "SELECT * FROM pos_order ORDER BY fact_id DESC LIMIT 0,1";
$lista_order = mysql_query($query_lista_order, $conexion) or die(mysql_error());
$row_lista_order = mysql_fetch_assoc($lista_order);
$totalRows_lista_order = mysql_num_rows($lista_order);
$order_id=$row_lista_order['order_id'];
$pos_reference=$row_lista_order['pos_reference'];

mysql_select_db($database_conexion, $conexion);
$query_lista_lineas_order = "SELECT * FROM pos_order_line WHERE pos_reference='$pos_reference' ORDER BY id ASC";
$lista_lineas_order = mysql_query($query_lista_lineas_order, $conexion) or die(mysql_error());
$row_lista_lineas_order = mysql_fetch_assoc($lista_lineas_order);
$totalRows_lista_lineas_order = mysql_num_rows($lista_lineas_order);

function formato_num_s($num){
round($num,2);
$valor=number_format($num,2,',','.');
return $valor;
}

?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link href="CCS/estilos_web.css" rel="stylesheet" type="text/css" />
</head>

<body marginheight="0" marginwidth="0">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#333333" height="38">&nbsp;<img src="imagenes/logo.png" width="87" height="auto"></td>
    <td bgcolor="#333333"><div align="right"><a href="<?php echo $ip;?>" class="texto_reloj2">Cerrar</a></div></td>
    <td bgcolor="#333333">&nbsp;</td>
  </tr>
</table>
<form name="form1" enctype="multipart/form-data" method="post" action="index1.php">
<table width="84%" border="0" align="center" class="sombra">
  <tr>
    <td bgcolor='#3D3D3D'>
	<div class="texto_Titulo">Cliente: <?php echo $row_lista_order['cliente']; ?></div>
	<div class="texto_Titulo">Cédula: <?php echo $row_lista_order['cedula']; ?></div>
	<div class="texto_Titulo">Teléfono: <?php echo $row_lista_order['telefono']; ?></div>
	<div class="texto_Titulo">Dirección: <?php echo $row_lista_order['direccion']; ?></div>
	</td>
	<td align="center" bgcolor='#3D3D3D'>	<div class="texto_Titulo">
	  Factura Nro:&nbsp;&nbsp;<input type="text" name="fact_id" value="<?php echo $row_lista_order['fact_id']; ?>" size="5"> 
	  </div>
	</td>
  </tr>
  <tr>
    <td width="50%" colspan="2">
	<!--------------------------- ARE 1 ---------------------------------->
	<table border="0" width="100%" align="center">
		  <tr align='center' class="texto_Titulo" bgcolor='#A24689'>
			<td>id</td>
			<td>Producto</td>
			<td>Precio Unitario </td>
			<td>Descuento </td>
			<td>Cantidad</td>
		  <td>Total Base</td>
		    <td>Alicuota (%)</td>
		  <td>Total</td>
		  </tr>
		  <?php $enum=0; do { $enum=$enum+1; $k=$enum%2;?>
		  <tr align="center" class="niveles" bgcolor="<?php echo $barra[$k];?>"onMouseOver="this.style.backgroundColor='#EEF1F5';" onMouseOut="this.style.backgroundColor='<?php echo $barra[$k] ;?>';">
			<td><?php echo $enum; ?></td>
			<td><?php echo $row_lista_lineas_order['producto']; ?></td>
			<td><?php echo formato_num_s($row_lista_lineas_order['price_unit']); ?></td>
			<td><?php echo $row_lista_lineas_order['descuento']; ?></td>
			<td><?php echo $row_lista_lineas_order['cantidad']; ?></td>
		  <td><?php echo formato_num_s($total_par=($row_lista_lineas_order['price_unit']*$row_lista_lineas_order['cantidad']));?></td>
		    <td><?php echo $row_lista_lineas_order['valor_alicuota']; ?></td>
		  <td><?php echo formato_num_s($total_prod=($total_par*$row_lista_lineas_order['valor_alicuota']/100)+$total_par); $total=$total+$total_prod;?></td>
		  </tr>
		  <?php } while ($row_lista_lineas_order = mysql_fetch_assoc($lista_lineas_order)); ?>
		  <tr align="center" class="niveles">
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
			<td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>&nbsp;</td>
		    <td>Total a pagar: </td>
		    <td><?php echo formato_num_s($total);?></td>
	    </tr>
	</table>
	<!----------------------------FIN AREA 1 --------------------------------->	</td>
    <!--td width="50%">&nbsp;</td-->
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="center"><?php if($accion<>"2"){?><a href="recibo_pago.php?id_order=<?php echo $order_id; ?>" class='boton_nuevo_registro'>Generar Recibo Pago</a><?php }?></td>
    <td align="center">
	<?php //if($accion<>"2"){?><button>Generar Factura Fiscal</button>
	<?php //}?>
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php if($accion=="2"){?>
	<!--a href='<?php echo $ip;?>' class="boton_continuar">Continuar >>
    </a-->	<?php }?></td>
  </tr>
  <tr>
    <td><input type="hidden" name="accion" value="2">
      <input type="hidden" name="id_order" value="<?php echo $order_id; ?>"></td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>