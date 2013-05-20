<select name='idciudad' id="idciudad">
<?php foreach ($ciudades as $ciudad) { ?>
<option value="<?php echo $ciudad->getIdCiudad(); ?>"><?php echo $ciudad->getCiudad(); ?></option>
<?php } ?>
</select>
