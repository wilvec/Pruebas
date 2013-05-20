<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title><?php echo $titulo; ?></title>
    </head>
    <body>
        <p>
          <?php include HOME . DS . 'includes' . DS . 'menu.php'; ?>
        </p>
        <h2>USUARIOS DE LA APLICACION</h2>
        <table border="1" width="500" cellspacing="0" cellpadding="0">
            <thead>
                <tr>
                    <th>Documento</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Fecha Nacimiento</th>
                    <th>Ciudad de nacimiento</th>
                </tr>
          </thead>
            <tbody>
                <?php foreach ($usuarios as $user) { ?>
                <tr>
                    <td><a href="/Pruebas/usuario/detalle/<?php echo $user->getDocumento();?>">
                            <?php echo $user->getDocumento();?></a></td>
                    <td><?php echo $user->getNombre();?></td>
                    <td><?php echo $user->getApellido();?></td>
                    <td><?php echo $user->getFechaNacimiento()->format('Y-m-d');?></td>
                    <td><?php echo $user->getCiudadNacimiento();?></td>
                </tr>
                <?php } ?>
            </tbody>
    </table>
        <br><br>
        <a href="/Pruebas/usuario/agregar">Nuevo usuario</a>
    </body>
</html>