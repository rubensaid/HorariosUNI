<?php
//http://flowplayer.org/tools/demos/validator/custom-validators.html

//Titulo
$title="Contactame";

//JS
$js='<script type="text/javascript" src="js/validator.js"></script>
	$("#contactame").validator({ 
		position: "top left", 
		offset: [-12, 0],
		message: "<div><em/></div>" // em element is the arrow
	});';

//CSS
$css='<link rel="stylesheet" type="text/css" href="css/toolbox.css"/>';
	
//Contenido
$content='<div style="text-align: justify; padding: 10px;">
<h2>Contactame</h2>';
$mail_destinatario = 'webmaster@code09fim.com';
if(isset($_POST['enviar']) and empty($_POST['facultad'])) {
	$headers="From: ".$_POST['email']."\n";
if(empty($_POST['email']) or empty($_POST['asunto']) or empty($_POST['nombre']) or empty($_POST['mensaje'])) {
	$content.='<div id="msgimportanta">Todos los campos son requeridos.</div>'; 
} else {
	if (mail($mail_destinatario, $_POST['asunto'], "Nombre y apellidos: ".$_POST['nombre']." Asunto: ".stripcslashes($_POST['asunto'])."n Mensaje :n".stripcslashes($_POST['mensaje']), $headers)) {
		$content.='<div id="msgimportant">Su mensaje a sido enviado correctamente. Gracias por contactar con nosostros.</div>'; 
	} else { 
		$content.='<div id="msgimportantf">Su mensaje no ha podido ser enviado. Vuelvalo a intentar mas tarde.</div>'; 
	}
}
}

$content.='
<form method="post" name="contactame">
<label for="nombre">Nombre y apellidos :</label> 
<input type="text" name="nombre" size="50" maxlength="80" required="required">
<br />
<label for="email">Email :</label>
<input type="text" type="email" name="email" size="50" maxlength="60" required="required" minlength="9" />
<br />
<label for="asunto">Asunto :</label>
<input type="text" name="asunto" size="50" maxlength="60" required="required" minlength="5" />
<br />
<label for="mensaje">Mensaje :</label>
<textarea name="mensaje" cols="31" rows="5" required="required"></textarea> <br />
<input type="text" name="facultad" class="ocu" />
<input type="submit" name="enviar" value="Enviar consulta">
</form></div>';
?>