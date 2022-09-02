<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php');
$order_id=$_REQUEST["id_order_afectado"];
$pos_reference=$_REQUEST["pos_reference"];
$order_nc=$_REQUEST["order_nc"];
$line=$_REQUEST["line"];
$opcion=$_REQUEST["opcion"];
$accion=$_REQUEST["accion"];
$num_item=$_REQUEST["num_item"];
$fact_afect=$_REQUEST["fact_afect"];
$barra[0]="#FFFFFF";
$barra[1]="#E5E5E5";
 ?>
<?php
if($opcion==3){
mysql_select_db($database_conexion, $conexion);
$query_ocultar = "UPDATE pos_order_line SET ocultar='si' WHERE id = '$line'";
$ocultar = mysql_query($query_ocultar, $conexion) or die(mysql_error());
}

if($accion==2 || $accion==3){
$num_item;
mysql_select_db($database_conexion, $conexion);
	for($i=1; $i<=$num_item;$i=$i+1){
	$precio=$_REQUEST["price_unit".$i];
	$qyt=$_REQUEST["cantidad".$i];
	$linea=$_REQUEST["line_actualiza".$i];
	$query_actualiza = "UPDATE pos_order_line SET cantidad='$qyt', price_unit='$precio' WHERE id = '$linea'";
	$actualiza = mysql_query($query_actualiza, $conexion) or die(mysql_error());
	$query_actualiza2 = "UPDATE pos_order SET fact_afect='$fact_afect' WHERE order_id = '$order_id'";
	$actualiza2 = mysql_query($query_actualiza2, $conexion) or die(mysql_error());
	}
}
if($fact_afect=="" && $accion<>""){
echo '<script> alert("Debe ingresar el nro de la Factura Afectada.");</script>';
}

if($accion==3 && $fact_afect<>""){
include("credito_prn.php");
exec('c:\WINDOWS\system32\cmd.exe /c START C:\wamp\www\fiscal_13\credito.bat');
echo '<script> alert("Generando Impresion de la nota de cr�dito con exito.");</script>';	

$query_nc = "INSERT INTO pos_secuencia_nc (pos_order_id) VALUES ('$order_nc')";
$ocultar = mysql_query($query_nc, $conexion) or die(mysql_error());

exec('c:\WINDOWS\system32\cmd.exe /c START C:\wamp\www\fiscal_13\actualiza_nc.bat');
}


mysql_select_db($database_conexion, $conexion);
$query_lista_ordenes = "SELECT * FROM pos_order WHERE pos_reference = '$pos_reference'";
$lista_ordenes = mysql_query($query_lista_ordenes, $conexion) or die(mysql_error());
$row_lista_ordenes = mysql_fetch_assoc($lista_ordenes);
$totalRows_lista_ordenes = mysql_num_rows($lista_ordenes);
$id_order=$row_lista_ordenes['order_id'];



mysql_select_db($database_conexion, $conexion);
$query_lista_lineas = "SELECT * FROM pos_order_line WHERE order_id = '$id_order' AND ocultar!='si'";
$lista_lineas = mysql_query($query_lista_lineas, $conexion) or die(mysql_error());
$row_lista_lineas = mysql_fetch_assoc($lista_lineas);
$totalRows_lista_lineas = mysql_num_rows($lista_lineas);

?>
<html>
<head>
<link href="CCS/estilos_web.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body marginheight="0" marginwidth="0">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#333333" height="38">&nbsp;<img src="imagenes/logo.png" width="87" height="auto"></td>
    <td bgcolor="#333333"><div align="right"><a href="<?php echo $ip;?>" class="texto_reloj2">Cerrar</a></div></td>
    <td bgcolor="#333333">&nbsp;</td>
  </tr>
</table>
<form name="form1" method="post" action="nota_credito.php?accion=3&id_order_afectado=<?php echo $order_id;?>&order_nc=<?php echo $order_nc; ?>&pos_reference=<?php echo $pos_reference;?>">
<table width="84%"  border="0" align="center" class="sombra">
  <tr bgcolor='#3D3D3D'>
    <td><div class="texto_Titulo"><?php echo $row_lista_ordenes['cliente']; ?></div>
      <div class="texto_Titulo"><?php echo $row_lista_ordenes['cedula']; ?></div></td>
  </tr>
  <tr>
    <td>
	<!-- inicio -->	<table border="0" align="center" width="100%">
  <tr height="40">
    <td colspan="2">&nbsp;<button>Guardar</button></td>
    <td>&nbsp;</td>
    <td colspan="2"><div align="right" class="texto_ver_mas">Ingrese Nro Factura Afectada:</div></td>
    <td><div align="center">
      <input type="text" name="fact_afect" size="5" value="<?php echo $row_lista_ordenes['fact_afect']; ?>">
    </div></td>
  </tr>
  <tr align='center' class="texto_Titulo" bgcolor='#A24689'>
    <td><div align="center">#</div></td>
    <td>Producto a Devolver </td>
    <td>Precio Unitario </td>
    <td>Cantidad a Devolver </td>
    <td>Sub Total </td>
    <td>Excluir</td>
  </tr>
  <?php $enum=0; do { $enum=$enum+1; $k=$enum%2; ?>
  <tr align="center" class="niveles" bgcolor="<?php echo $barra[$k];?>"onMouseOver="this.style.backgroundColor='#EEF1F5';" onMouseOut="this.style.backgroundColor='<?php echo $barra[$k] ;?>';">
    <td><?php echo $enum; ?></td>
    <td><?php echo $row_lista_lineas['producto']; ?></td>
    <td>     
        <input type="text" name="price_unit<?php echo $enum; ?>" value="<?php echo $row_lista_lineas['price_unit']; ?>">
    </td>
    <td><input type="text" name="cantidad<?php echo $enum; ?>" value=" <?php echo $row_lista_lineas['cantidad']; ?>"></td>
    <td><?php echo $sub=($row_lista_lineas['cantidad']*$row_lista_lineas['price_unit']);?>
      <input type="hidden" name="line_actualiza<?php echo $enum; ?>" value="<?php echo $row_lista_lineas['id']; ?>"></td>
    <td><a href="nota_credito.php?id_order_afectado=<?php echo $order_id;?>&order_nc=<?php echo $order_nc; ?>&line=<?php echo $row_lista_lineas['id']; ?>&opcion=3"><img src="imagenes/basura.png" width="20" height="auto" border="0"></a></td>
  </tr>
  <?php } while ($row_lista_lineas = mysql_fetch_assoc($lista_lineas)); ?>
</table>
	<!-- fin -->
	</td>
  </tr>
  <tr>
  <td>&nbsp;<input type="hidden" name="num_item" value="<?php echo $enum;?>"></td>
  </tr>
  <tr height="40">
  <td align="center">&nbsp;<button>Nota de Cr�dito</button>
  <!--a href="nota_credito.php?accion=3&id_order_afectado=<?php //echo $order_id;?>" class="boton_nuevo_registro">Nota de Credito</a-->
  </td>
  </tr>
</table>


</form>  
</body>
</html>