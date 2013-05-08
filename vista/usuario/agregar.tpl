<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $titulo; ?></title>
        <script type="text/javascript">
            //Funcion de Ajax
            function getAjax() {
                var xmlhttp;
                if (window.XMLHttpRequest) {
                    xmlhttp = new XMLHttpRequest();
                } else {
                    xmlhttp = new ActiveObject("Microsoft.XMLHTTP");
                }
                return xmlhttp;
            }

            function llenarCiudades(depto) {
                var ajax = getAjax();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4) {
                        if (ajax.status == 200) {
                            var datos = ajax.responseText;
                            alert(datos);
                        }
                    }
                }
                ajax.open("GET", "/Pruebas/usuario/listarciudad/" + depto, true);
                ajax.send(null);
            }
            window.onload = function() {
                document.getElementById('deptos').onchange = function() {
                    var depto = document.getElementById('deptos').options[document.getElementById('deptos').selectedIndex].value;
                    llenarCiudades(depto);
                }
            }
        </script>
    </head>
    <body>
        <p>
            <?php include HOME . DS . 'includes' . DS . 'menu.php'; ?>
        </p>
        <h2>DATOS</h2>
        <form action="/Pruebas/usuario/guardar" method="post" name="form1">
            <table width="416" border="1" cellspacing="0" cellpadding="2">
                <tr>
                    <th width="197" scope="row">Documento</th>
                    <td width="211"><input name="documento" id="documento" type="text" /></td>
                </tr>
                <tr>
                    <th scope="row">Nombre</th>
                    <td><input name="nombre" id="nombre" type="text" /></td>
                </tr>
                <tr>
                    <th scope="row">Apellido</th>
                    <td><input name="apellido" id="apellido" type="text" /></td>
                </tr>
                <tr>
                    <th scope="row">Fecha de Nacmiento</th>
                    <td><input name="fechanacimiento" id="fechanacimiento" type="text" /></td>
                </tr>
                <tr>
                    <th scope="row">Ciudad de Nacimiento</th>
                    <td>
                        <select name="deptos" id="deptos">
                            <?php foreach($deptos as $depto){ ?>
                            <option value="<?php echo $depto['iddepto']; ?>"><?php echo $depto['departamento'];?></option>
                            <?php } ?>
                        </select>
                        <br>
                        <div id="combociudad"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2"><input name="agregarusuario" id="agregarusuario" type="submit" value="Guardar" /></td>
                </tr>

            </table>
        </form>
        <p>&nbsp;</p>
    </body>
</html>