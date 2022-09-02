<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('Connections/conexion.php');
//$ip=$host.':'.$puerto_apli; // ,i ip

$nro_puerto = fopen("Puerto.dat", "r");
$puerto = fgets($nro_puerto);    
fclose($nro_puerto);

?>
<HTML>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<link href="CCS/estilos_web.css" rel="stylesheet" type="text/css" />
<head>
<title>Demo PHP IntTFHKA</title>
</head>
<BODY marginheight="0" marginwidth="0">
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td bgcolor="#333333" height="38">&nbsp;<img src="imagenes/logo.png" width="87" height="auto"></td>
    <td bgcolor="#333333"><div align="right"><a href="<?php echo $ip;?>" class="texto_reloj2" target="_self">Volver</a></div></td>
    <td bgcolor="#333333">&nbsp;</td>
  </tr>
</table><br><br>

<div align = "center"><br>
<B>MODULO DE REPORTE DIARIO DE CAJA </B><br>
<br>
	
	<form id="form1" name="form1" method="post" action = "reporte_diario.php">
           <!-- <input name ="EnviarComando" type = "submit"  value="SetPort" />--></br></br>
            <!--<input name ="EnviarComando" type = "submit"  value="ReporteX" />-->
            <!--<input name ="EnviarComando" type = "submit"  value="ReporteZ" />-->
            <button value="ReporteX" name="EnviarComando">Reporte X</button>
            <button value="ReporteZ" name="EnviarComando">Reporte Z</button>
            <input type="hidden" name="PortName" value="<?php echo $puerto; ?>">
	</form>
</div>
<?php
	include_once ("TfhkaPHP.php"); 

         $Foperacion = null;
    if(isset($_POST["EnviarComando"]))
    { echo $Foperacion = $_POST["EnviarComando"]; }

    $itObj = new Tfhka();

if (isset($Foperacion)){
  $out = "";
   if ($Foperacion == "Enviar") {
                $out =  $itObj->SendCmd($_POST["Comando"]);
	  }elseif ($Foperacion == "SubirS1") {
			$out =  $itObj->UploadStatusCmd("S1", "StatusData.txt");
	  }elseif ($Foperacion == "SubirS2") {
			$out =  $itObj->UploadStatusCmd("S2", "StatusData.txt");
	  }elseif ($Foperacion == "SubirS3") {
			$out =  $itObj->UploadStatusCmd("S3", "StatusData.txt");
	  }elseif ($Foperacion == "SubirS4") {
			$out =  $itObj->UploadStatusCmd("S4", "StatusData.txt");
	  }elseif ($Foperacion == "SubirS5") {
			$out =  $itObj->UploadStatusCmd("S5", "StatusData.txt");
	  }elseif ($Foperacion == "SubirS6") {
			$out =  $itObj->UploadStatusCmd("S6", "StatusData.txt");
	  }elseif ($Foperacion == "SubirU0X") {
			$out =  $itObj->UploadReportCmd("U0X" , "ReportData.txt");
	  }elseif ($Foperacion == "SubirU0Z") {
			$out =  $itObj->UploadReportCmd("U0Z" , "ReportData.txt");
	  }elseif ($Foperacion == "Facturar") {
	        $factura = array(0 => "!000000100000001000Harina\n",
							 1 => "!000000150000001500Jamon\n",
							 2 => '"000000205000003000Patilla\n',
							 3 => "#000005000000001000Caja de Whisky\n",
							 4 => "101");
		$file = "Factura.txt";	
                $fp = fopen($file, "w+");
                $write = fputs($fp, "");
                         
			foreach($factura as $campo => $cmd)
			{
		     	   $write = fputs($fp, $cmd);
			}
                        
                         fclose($fp); 
                         
                         $out =  $itObj->SendFileCmd($file);
                         
	  }elseif ($Foperacion == "Devolucion") {
	        $devolucion = array(-5 => "iS*Pedro Mendez\n",
							-4 => "iR*12.345.678\n",
							-3 => "iF*0000001\n",
			                -2 => "iI*Z4A1234567\n",
							-1 => "iD*18-01-2014\n",
							 0 => "d0000000100000001000Harina\n",
							 1 => "d1000000150000001500Jamon\n",
							 2 => 'd2000000205000003000Patilla\n',
							 3 => "d3000005000000001000Caja de Wisky\n",
							 4 => "101");
							 
			$file = "NotaCredito.txt";	
                $fp = fopen($file, "w+");
                $write = fputs($fp, "");
                         
			foreach($devolucion as $campo => $cmd)
			{
		     	   $write = fputs($fp, $cmd);
			}
                        
                         fclose($fp); 
                         
                         $out =  $itObj->SendFileCmd($file);
                         
	  }elseif ($Foperacion == "ReporteX") {
			$out =  $itObj->SendCmd("I0X");
	  }elseif ($Foperacion == "ReporteZ") {
			$out =  $itObj->SendCmd("I0Z");
	  }elseif ($Foperacion == "SetPort") {
		       $itObj->SetPort($_POST["PortName"]);
	  }	
//echo $_POST["PortName"];
   if($out == "ASK")
   {
       echo "<div align = 'center'><B><font color = 'green' size = '9'>TRUE</font></B></div>";
   }elseif($out == "NAK")
   {
       echo "<div align = 'center'><B><font color = 'red' size = '9'>FALSE</font></B></div>";
   }else
   {
      echo "<div align = 'center'>".$out."</div>";
   }
   	  
    //echo "<br><br><div align = 'center'>".$itObj->Log."</div>";
}
	
?>
 </div>

</BODY>
</HTML>
