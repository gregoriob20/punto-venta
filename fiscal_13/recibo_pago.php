<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php');
#$ip=$host.':'.$puerto_apli;
#$ip=$host;
$id_order=$_REQUEST["id_order"];
$opcion=$_REQUEST["opcion"];
$medidaTicket = 320;
 ?>
<?php
mysql_select_db($database_conexion, $conexion);
$query_datos_orden = "SELECT * FROM pos_order WHERE order_id = '$id_order'";
$datos_orden = mysql_query($query_datos_orden, $conexion) or die(mysql_error());
$row_datos_orden = mysql_fetch_assoc($datos_orden);
$totalRows_datos_orden = mysql_num_rows($datos_orden);

mysql_select_db($database_conexion, $conexion);
$query_lista_line_order = "SELECT * FROM pos_order_line WHERE order_id = '$id_order'";
$lista_line_order = mysql_query($query_lista_line_order, $conexion) or die(mysql_error());
$row_lista_line_order = mysql_fetch_assoc($lista_line_order);
$totalRows_lista_line_order = mysql_num_rows($lista_line_order);

function formato_num_s($num){
round($num,2);
$valor=number_format($num,2,',','.');
return $valor;
}
if($opcion=='imprimir'){
mysql_select_db($database_conexion, $conexion);
$query_actualiza = "UPDATE pos_order SET status_local='done' WHERE order_id = '$id_order'";
$actualiza = mysql_query($query_actualiza, $conexion) or die(mysql_error());
}
?>
<html>
<head>

<style>
button {
 border: none;
 background: #00A810;
 color: #f2f2f2;
 padding: 8px;
 font-size: 25px;
 border-radius: 5px;
 position: relative;
 box-sizing: border-box;
 transition: all 500ms ease;
}
button:before {
 content:'';
 position: absolute;
 top: 0px;
 left: 0px;
 width: 0px;
 height: 42px;
 background: rgba(255,255,255,0.3);
 border-radius: 5px;
 transition: all 2s ease;
}
button:hover:before {
 width: 100%;
}
        * {
            font-size: 12px;
            font-family: 'DejaVu Sans', serif;
        }

        h1 {
            font-size: 18px;
        }

        .ticket {
            margin: 2px;
        }

        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            margin: 0 auto;
        }

        td.precio {
            text-align: right;
            font-size: 11px;
        }

        td.cantidad {
            font-size: 11px;
        }

        td.producto {
            text-align: center;
        }

        th {
            text-align: center;
        }


        .centrado {
            text-align: center;
            align-content: center;
        }

        .ticket {
            width: <?php echo $medidaTicket ?>px;
            max-width: <?php echo $medidaTicket ?>px;
        }

        img {
            max-width: inherit;
            width: inherit;
        }

        * {
            margin: 0;
            padding: 0;
        }

        .ticket {
            margin: 0;
            padding: 0;
        }

        body {
            text-align: center;
        }
    .Estilo1 {font-size: 13px}
 .Estilo2 {
	color: #FFFFFF;
	text-decoration: none;
}
</style>
  <SCRIPT language="javascript"> 
    function imprimir() { 
        if ((navigator.appName == "Netscape")) { window.print() ; 
        } 
        else {
            var WebBrowser = '<OBJECT ID="WebBrowser1" WIDTH=0 HEIGHT=0 CLASSID="CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>'; 
            document.body.insertAdjacentHTML('beforeEnd', WebBrowser); WebBrowser1.ExecWB(6, -5); WebBrowser1.outerHTML = ""; 
        } 
    } 
</SCRIPT>
</head>

<body  <?php if($opcion=='imprimir'){ ?>  onLoad='imprimir();' <?php }?>>
<?php if($opcion=='imprimir'){ ?>
<meta http-equiv="Refresh" content="0;url=<?php echo $ip;?>">
<?php }?>
<?php if($opcion!='imprimir'){?>
<table width="100%"  border="0">
  <tr>
    <td bgcolor="#393939"><img src="imagenes/logo.png" width="auto" height="50"></td>
    <td bgcolor="#393939"><div align="center"><a href="<?php echo $ip;?>" class="Estilo2">REGRESAR</a></div></td>
  </tr>
</table>
<br>
<?php }?>
<div class="ticket centrado">
<h1><?php echo $row_datos_orden['compania'];?></h1>
<h2>Cliente: <?php echo $row_datos_orden['cliente'];?></h2>
<h2><?php echo $row_datos_orden['pos_reference'];?></h2>
<h2>Fecha:<?php echo date("d-m-Y");?></h2>
<div>&nbsp;</div>
<table border="0" width=<?php echo $medidaTicket; ?>>
  <tr align="center" class="producto">
    <td><strong>#</strong></td>
    <td><strong>Concepto</strong></td>
    <td><strong>Precio Unit </strong></td>
    <td><strong>Cantidad</strong></td>
    <td><strong>Sub Total </strong></td>
  </tr>
  <?php $enum=0;$acum_sub=0; $acum_general=0; $acum_redu=0; $acum_adic=0; $ban_gene=99; $ban_redu=99; $ban_adic=99; 
  $alicuota_gene=$alicuota_redu=$alicuota_adic=0;
  $imp_gene=$imp_redu=$imp_adic=0;
  do { $enum=$enum+1; ?>
  <tr>
    <td><?php echo $enum; ?></td>
    <td align="center" class="producto"><?php echo $row_lista_line_order['producto']; ?></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($row_lista_line_order['price_unit']); ?></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($row_lista_line_order['cantidad']); ?></td>
    <td align="right" class="Estilo1">
	<?php echo "&nbsp;".formato_num_s($sub_total=($row_lista_line_order['price_unit']*$row_lista_line_order['cantidad'])); $acum_sub=$acum_sub+$sub_total;
	if($row_lista_line_order['tipo_doc']==1){$acum_general=$acum_general+$sub_total; $ban_gene=1; $imp_gene=$row_lista_line_order['valor_alicuota'];}
	if($row_lista_line_order['tipo_doc']==2){$acum_redu=$acum_redu+$sub_total; $ban_redu=2; $imp_redu=$row_lista_line_order['valor_alicuota'];}
	if($row_lista_line_order['tipo_doc']==3){$acum_adic=$acum_adic+$sub_total; $ban_adic=3; $imp_adic=$row_lista_line_order['valor_alicuota'];}
	?>
	</td>
  </tr>
  <?php } while ($row_lista_line_order = mysql_fetch_assoc($lista_line_order)); ?>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2"><div align="right">Sub Total:&nbsp;&nbsp;</div></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($acum_sub);?></td>
  </tr>
  <?php if($ban_gene==1){?>
  <tr>
    <td colspan="4"><div align="right">Iva (<?php echo formato_num_s($imp_gene);?>%)</div></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($alicuota_gene=($acum_general*$imp_gene/100));?></td>
  </tr>
  <?php } if($ban_redu==2){?>
  <tr>
    <td colspan="4"><div align="right">Iva (<?php echo formato_num_s($imp_redu);?>%)</div></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($alicuota_redu=($acum_redu*$imp_redu/100));?></td>
  </tr>
  <?php } if($ban_adic==3){?>
  <tr>
    <td colspan="4"><div align="right">Iva (<?php echo formato_num_s($imp_adic);?>%)</div></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($alicuota_adic=($acum_adic*$imp_adic/100));?></td>
  </tr>
  <?php }?>
  <tr>
    <td colspan="4"><div align="right">Total a Pagar: </div></td>
    <td align="right" class="Estilo1"><?php echo formato_num_s($total=($acum_sub+$alicuota_gene+$alicuota_redu+$alicuota_adic));?></td>
  </tr>
</table>
<br><br>
<?php if($opcion!='imprimir'){?>
<form name="form1" method="post" enctype="multipart/form-data" action="recibo_pago.php?opcion=imprimir&id_order=<?php echo $id_order;?>"> 
  <button>Imprimir Recibo</button>
</form>
<?php } ?>
</div>
</body>
</html>