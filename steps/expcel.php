<?php
//http://www.webintenta.com/exportar-tablas-html-a-excel-con-php-y-jquery.html
header("Content-type: application/vnd.ms-excel; name='excel'");
header("Content-Disposition: filename=Horario.xls");
header("Pragma: no-cache");
header("Expires: 0");

echo $_POST['datos_a_enviar'];
?>