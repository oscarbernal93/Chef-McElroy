<?php 
include_once 'lenguaje_natural.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Descubrir una fruta</title>
	<link href="estilos.css" rel="stylesheet">
</head>
<body>
<p>dejame pensarlo...</p>
<p>

<?php 
$plegaria = htmlspecialchars($_GET["plegaria"]);
$plegarias = explode("\r\n", $plegaria);
$momo = new Interprete();
?>
<pre>
<?php 
	$propiedades = array();
	foreach ($plegarias as $plegaria):
		echo "\n*";
		$resultado_lexico = $momo->lex($plegaria);
		$resultado_sintactico = $momo->sintax($resultado_lexico);
	endforeach;
?>
</pre>
</p>

</body>
</html>