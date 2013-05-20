<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Olvid&eacute; mi contrase&ntilde;a</title>
</head>
<body>
<h2>CAMBIAR CONTRASE&Ntilde;A</h2>
<form name="form1" id="form1" method="post" action="/Pruebas/usuario/guardarclave">
<table width="440" border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td colspan="2">Se le enviará un correo con las instrucciones de recuperación</td>
    </tr>
  <tr>
    <td width="181">Documento de Identidad</td>
    <td width="251"><input name="documento" type="text" id="documento" readonly="readonly" value="<?php echo $documento; ?>" /></td>
  </tr>
  <tr>
    <td>Usuario</td>
    <td><input name="usuario" type="text" id="usuario" readonly="readonly" value="<?php echo $nombreusuario; ?>"  /></td>
  </tr>
  <tr>
    <td>Nueva contraseña</td>
    <td><input type="text" name="clave" id="clave" /></td>
  </tr>
  <tr>
    <td>Confirmar contraseña</td>
    <td>//TODO!!</td>
  </tr>
  <tr>
    <td colspan="2" align="center"><input type="submit" name="botonenviar" id="botonenviar" value="Enviar" />    </td>
  </tr>
</table>
</form>
</body>
</html>
