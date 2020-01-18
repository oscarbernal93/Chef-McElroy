<?php 
include_once 'db.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aprendiendo Palabras Nuevas</title>
	<link href="estilos.css" rel="stylesheet">
</head>
<body>
<p>estoy aprendiendo...</p>
<p>
<pre>
<?php 
$database = new Conocimiento;
foreach ($_GET as $key => $value) {
	if (!is_numeric($value) and 'NA'!=$value) {
		# si es numerico es un valor
		if ('T_ADVERBIO'==$value) {
			# si es un adverbio debe tener un valor
			if ($val = $_GET[$key."_value"]) {
				# si el valor existe aprende la palabra
				$database->palabrear($key,$value,$val);
				echo "aprendí que $key vale $val \n";
			}
		}else{
			# no es un adverbio
			$database->palabrear($key,$value);
			echo "aprendí a reconocer $key \n";
		}
	}
}
?>
</pre>
<button onclick="window.history.back(2);">Regresar!</button>

</body>
</html>