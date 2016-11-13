<?php 
include_once 'lenguaje_natural.php';
include_once 'elementos_lexicos.php';
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Descubrir una fruta</title>
</head>
<body>
<p>dejame pensarlo...</p>
<pre>

<?php 
$plegaria = htmlspecialchars($_GET["plegaria"]);
$plegarias = explode(",", $plegaria);
$momo = new Interprete($elementos);
echo $plegaria."\n";
foreach ($plegarias as $elemento) {
	$resultado_lexico = $momo->lex($elemento);
	foreach ($resultado_lexico as $palabra) {
		echo $palabra[1]." ";
	}
	echo "\n";
}
?>
</pre>

</body>
</html>