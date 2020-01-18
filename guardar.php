<?php 
include_once 'lenguaje_natural.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Guardar una fruta</title>
	<link href="estilos.css" rel="stylesheet">
</head>
<body>
<p>estoy aprendiendo...</p>
<p>
<pre>

<?php 
$propiedades = json_decode($_GET["propiedades"],TRUE);
$nombre = $_GET["nombre"];
$momo = new Interprete();
$r = $momo->aprender($nombre,$propiedades);
echo "he aprendido muchas cosas sobre la fruta: $nombre\n";
echo "he actualizado ".$r[0]." registros, de los cuales ".$r[1]." son nuevos.";
?>
</pre>
<button onclick="window.history.back();">Regresar!</button>

</body>
</html>