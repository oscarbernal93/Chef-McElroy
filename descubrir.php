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
<div>
<?php 
echo $plegaria;
?>
</div>
<ul>
	<?php 
	foreach ($plegarias as $plegaria):
		$resultado_lexico = $momo->lex($plegaria);
	var_dump();
		$resultado_sintactico = $momo->sintax($resultado_lexico);
	?>
	<li class="<?php echo $resultado_sintactico; ?>">
		<?php echo $plegaria ?>
		<span class="lex">
		<?php 
		foreach ($resultado_lexico as $palabra) {
			if ($palabra[1] == "T_ESPACIO") {
				echo " ";
			}else{
				echo $palabra[1];//."(".$palabra[0].")";
			}
		}
		?>
		</span>
	</li>

	<?php 
	endforeach;
	?>
</ul>
</p>

</body>
</html>