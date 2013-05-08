<?php
echo "{";
foreach ($ciudades as $ciudad) {
    echo "'".$ciudad->getIdCiudad()."':'".$ciudad->getCiudad()."',"; 
}
echo "}";
?>