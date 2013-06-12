<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $titulo; ?></title>
    </head>
    <body>
        <h2>ACCEDER</h2>
        <p>Accede a la aplicación con tu documento y clave registrados </p>
        <form name="form1" action="/Pruebas/usuario/entrar" method="post">
            <table border="1" width="500" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Documento</th>
                        <td><input name="documento" id="documento" type="text" /></td>
                    </tr>
                    <tr>
                        <th>Clave</th>
                        <td><input name="clave" id="clave" type="password" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input name="enviar" id="enviar" type="submit" value="Entrar" /></td>
                    </tr>
                    <tr>
                      <td colspan="2" align="center"><a href="/Pruebas/usuario/olvidoclave">Olvidé mi contraseña!</a></td>
                  </tr>
                </tbody>
            </table>
        </form>
        <p><br>
          <br>
          O también puedes acceder con:<br>
        <table width="200" border="0" cellspacing="0" cellpadding="2">
          <tr>
            <td align="center"><a href="/Pruebas/usuario/accesofb/face"><img src="../estatico/face.png" alt="facebook" width="48" height="48"></a></td>
            <td align="center"><a href="/Pruebas/usuario/accesofb/twitter"><img src="../estatico/twitter.png" alt="twitter" width="48" height="48"></a></td>
            <td align="center"><a href="/Pruebas/usuario/accesofb/google"><img src="../estatico/googlep.png" alt="google plus" width="48" height="48"></a></td>
          </tr>
        </table>
        <p>
    </body>
</html>