<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $titulo; ?></title>
    </head>
    <body>
        <h2>ACCEDER</h2>
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
        <br><br>
        <a href="/Pruebas/usuario/agregar">Nuevo usuario</a>
    </body>
</html>