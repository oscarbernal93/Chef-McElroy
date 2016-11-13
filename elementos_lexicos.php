<?php 
include_once 'db.php';

$elementos = array(
    '\s?\w+\s?'                     	=> 'T_PALABRA',
    ',\s'                     			=> 'T_SEPARADOR',
);


$database = new Conocimiento;
$a = $database->obtener_propiedad("adverbio");
$v = $database->obtener_propiedad("verbo");
$elementos = array_merge(array($v=>'T_VERBO'),$elementos);
$elementos = array_merge(array($a=>'T_ADVERBIO'),$elementos);

?>