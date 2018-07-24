<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
</head>
<?php
foreach($datos as $dato){
?>
<body style="margin:0;padding:0;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;" >
<div style="background-color:#fff;width:90%;border:1px solid #ccc;padding:10px 20px;margin:0 auto;font-family: Arial,Helvetica Neue,Helvetica,sans-serif;">
<p>
	Hola <?php echo $dato["nombre"]; ?>,
	<br><br>

	Hemos reseteado tu contrase&ntilde;a de acceso a la aplicaci&oacute;n <a href="http://intranet.km-t.cl/cpp" > Control productividad personal (CPP)</a>
	<br><br>
	tu nueva contrase&ntilde;a es <b><?php echo $pass; ?></b>
	<br><br>
	Puedes cambiar tu contrase&ntilde;a en la pesta&ntilde;a a la derecha del menu superior, clic en tu nombre, luego en cambiar contraseña.
	<br><br>
	Atentamente,
	<br><br>

</p>
      
</div>
</body>
<?php
}
?>
</html>
