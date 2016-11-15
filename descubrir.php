<?php 
include_once 'lenguaje_natural.php';
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
$momo = new Interprete();
echo $plegaria."\n ";
foreach ($plegarias as $plegaria) {
	$resultado_lexico = $momo->lex($plegaria);
	$resultado_sintactico = $momo->sintax($resultado_lexico);
	echo $plegaria.": ".$resultado_sintactico;
	echo "\n";
}
?>
</pre>

</body>
</html>