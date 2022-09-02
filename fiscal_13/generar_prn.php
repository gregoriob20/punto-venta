<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php'); 

$ruta='c:Python27/factfis/factura_real.prn';
unlink($ruta);
$id_order;

mysql_select_db($database_conexion, $conexion);
$query_datos_order = "SELECT * FROM pos_order WHERE order_id = '$id_order'";
$datos_order = mysql_query($query_datos_order, $conexion) or die(mysql_error());
$row_datos_order = mysql_fetch_assoc($datos_order);
$totalRows_datos_order = mysql_num_rows($datos_order);

mysql_select_db($database_conexion, $conexion);
$query_datos_line_order = "SELECT * FROM pos_order_line WHERE order_id = '$id_order'";
$datos_line_order = mysql_query($query_datos_line_order, $conexion) or die(mysql_error());
$row_datos_line_order = mysql_fetch_assoc($datos_line_order);
$totalRows_datos_line_order = mysql_num_rows($datos_line_order);

function tipo_doc($compara){
if($compara==0){ /*echo exento;*/ $simbolo="0";} // exento
if($compara==1){ /*echo iva;*/ $simbolo="1";}  // no exento, es decir, tiene iva 16%;
if($compara==2){ /*echo iva;*/ $simbolo='2';}  // no exento, es decir, tiene iva 8%;
if($compara==3){ /*echo iva;*/ $simbolo="3";}  // no exento, es decir, tiene iva 22%;
if($compara==4){ /*echo exento;*/ $simbolo="0";} // no gravadas
  //echo $tax_id;
return $simbolo ;
}
function formato_num($num){
round($num,2);
$valor=number_format($num,2,',','.');
return $valor;
}

function formato_num3($num){
round($num,3);
$valor=number_format($num,3,',','.');
return $valor;
}
function completar_cero($campo,$digitos){
  $valor=strlen ($campo);
  $nro_ceros=$digitos-$valor;
  for($j=1;$j<=$nro_ceros;$j++){
    $campo="0".$campo;
  }
  return $campo;
}
function elimina_caracteres($cadena){
	//$resultado = intval(preg_replace('/[^0-9]+/', '', $cadena), 10);
	$resultado = str_replace('.', '', $cadena);
	#$resultado = preg_replace('/[^0-9]+/', '', $cadena);
    return $resultado;
	}
?>

<?php
$salto='
';
$separador='||';

$cabezal="iS*".$row_datos_order['cliente'].$salto;
$cabezal=$cabezal."iR*".$row_datos_order['cedula'].$salto;
$cabezal=$cabezal."i00"."Direccion:".$row_datos_order['direccion'].$salto; //i03
$cabezal=$cabezal."i01"."Telefonos: ".$row_datos_order['telefono'].$salto; //i04
$cabezal=$cabezal."i02"."Nro de Orden: ".$row_datos_order['pos_reference'].$salto;  //i05
$cabezal=$cabezal."i03".$caja." Cajero:".$row_datos_order['usuario'].$salto;  //i05

$nuevoarchivo = fopen("$ruta", "w+");    
fwrite($nuevoarchivo,$cabezal);	
fclose($nuevoarchivo);


$nuevoarchivo = fopen("$ruta", "a+");
$tipo_comando='GF+';
  do { 
  $tipo_doc=tipo_doc($row_datos_line_order['tipo_doc']);
  $cuerpo=$tipo_comando.$tipo_doc;
  $cuerpo=$cuerpo.completar_cero(elimina_caracteres(formato_num($row_datos_line_order['price_unit'],2)),12).$separador;
  #$cuerpo=$cuerpo.completar_cero(elimina_caracteres(formato_num($row_datos_line_order['price_unit'],2)),10);//9 originalmente
  $cuerpo=$cuerpo.completar_cero(elimina_caracteres(formato_num3($row_datos_line_order['cantidad'],3)),13).$separador; // 9 originalmente
  $cuerpo=$cuerpo.$row_datos_line_order['producto'].$salto;
  if($row_datos_line_order['descuento'] > 0){
    $descueto = elimina_caracteres($row_datos_line_order['descuento']);
    $cuerpo=$cuerpo.'p-'.str_pad($descueto, 4, "0") .$salto;   

  }
  fwrite($nuevoarchivo,$cuerpo);  
   } while ($row_datos_line_order = mysql_fetch_assoc($datos_line_order)); 
   
   $monto = "";
   $porciones = explode(".", $row_datos_order['monto_divisa']); 
   $monto = $monto.$porciones[0];
   if(strlen($porciones[1]) == 1){
      $monto = $monto.$porciones[1].'0';
   }else {
     $monto = $monto.$porciones[1];
   }
   if($row_datos_order['monto_divisa'] > 0){
      $cuerpo ="3".$salto;
      $cuerpo =$cuerpo.'22'.str_pad($monto , 13, "0",STR_PAD_LEFT).$salto;  
      $cuerpo =$cuerpo."101".$salto; 
      $cuerpo =$cuerpo."199".$salto; 

   }else{
      $cuerpo="i09Gracias por su Compra".$salto; 
      $cuerpo=$cuerpo."101";
   }
  
   fwrite($nuevoarchivo,$cuerpo);  	
   fclose($nuevoarchivo);
   ?>
