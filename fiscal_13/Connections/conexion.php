<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_conexion = "localhost";
$database_conexion = "odoo";
$username_conexion = "root";
$password_conexion = "";
$conexion = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR); 

$host_odoo=$hostname_conexion;
$host_odoo_sh="https://farmamax-qa-3593547.dev.odoo.com/pos/web?config_id=2#action=pos.ui&cids=1";#colocar la url de odoo en caso de trabajar con odoo sh
$puerto_apli=8079; // aqui es el puerto de la aplicacion en caso de trabajar local
$host = $hostname_conexion;// aqui es la direccion de la aplicacion
$caja="CAJA 03"; #nombre de la caja que estará asignada la maquina
$nro_maquina_fiscal='Z7C0016651';
$ip='http://'.$host.':'.$puerto_apli.'/pos/web/#action=pos.ui'; # habilirar si la conexion sera local, conexion directa con la base de datos postgres, comentar el codigo de abajo
#$ip=$host_odoo_sh; # habilitar si la conexion es con odoo sh, comentar el codigo de arriba
?>