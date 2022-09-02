<?php 
error_reporting(E_ALL ^ E_NOTICE);
$ruta='c:Python27/factfis/reimprimir.prn';

$reimprimir = $_REQUEST["reimprimir"];
print($reimprimir);
$nuevoarchivo = fopen("$ruta", "w+");    
fwrite($nuevoarchivo,$reimprimir);	
fclose($nuevoarchivo);

//exec('c:\WINDOWS\system32\cmd.exe /c START C:\wamp\www\fiscal_13\reimprimir.bat');
?>
<script>
    window.history.back()
</script>