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
//esto de dividir asi es un machetazo 
//por que de lo contrario lee solo hasta una palabra invalida
//y luego de eso se sale, hay que revisar eso en el futuro
$plegarias = preg_split("/(\r\n|\n|[,.]| y )/", $plegaria);
$momo = new Interprete();
?>
<pre>
<?php 
	$counter = 0;
	$e_counter = 0;
	foreach ($plegarias as $plegaria):
		$resultado_lexico = $momo->lex($plegaria);
		$resultado_sintactico = $momo->sintax($resultado_lexico);
		if($resultado_sintactico == "invalido"){
			$e_counter++;
		}
		$counter++;
	endforeach;
	$resultados = $momo->coincidencias();
	reset($resultados);
	$llave = key($resultados);
	$percent = (1 - $resultados[$llave])*100;
	echo "he leido $counter sentencias, ";
	echo "$e_counter sentencias incorrectas.\n";
	echo "parece ser: ".$llave.", estoy seguro un ".$percent."%";
?>
</pre>

</p>

</body>
</html>