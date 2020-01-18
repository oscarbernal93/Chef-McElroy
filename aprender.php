<?php 
include_once 'lenguaje_natural.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aprender Palabras Nuevas</title>
	<link href="estilos.css" rel="stylesheet">
</head>
<body>

<script type="text/javascript">
	function validar(caller){
		name= caller.name;
		name = name+'_value';
		if ("T_ADVERBIO" == caller.value) {	
		caller.insertAdjacentHTML('afterEnd',"<input style='margin-left:10px;' type='number' name='"+name+"'' step='0.1' min='0' max='1' placeholder='valor'>");
		}else{
			if (e = document.getElementsByName(name)[0]) {
				e.remove();
			}
		}
	}
</script>
<p>estoy aprendiendo...</p>
<pre>
<?php 
$desconocidas = array();
$palabras = json_decode($_GET["palabras"],TRUE);
foreach ($palabras as $frase) {
	foreach ($frase as $palabra) {
		if ("T_PALABRA_DESCONOCIDA" == $palabra[1]) {
			$desconocidas[]=$palabra[0];
		}
	}
}
?>
</pre>
<?php if (!empty($desconocidas)): ?>
<p>selecciona muy cuidadosamente el tipo de cada palabra que voy a aprender, si no estas seguro elige No Aprender (N/A).</p>
<p>Para los adverbios se debe especificar ademas el valor, un numero REAL que va de 0.0 a 1.0 y define que <i>"tanto"</i> representa esa palabra</p>
<form method="get" action="aprendiendo.php">
<ul>
<?php foreach ($desconocidas as $desco): ?>
	<li>
		<?php echo $desco ?><span style="margin-left:15px"></span>
		<select onchange="validar(this)" name="<?php echo $desco ?>">
			<option value="NA">N/A</option>
			<option value="T_ADJETIVO">ADJETIVO</option>
			<option value="T_ARTICULO">ARTICULO</option>
			<option value="T_SUSTANTIVO">SUSTANTIVO</option>
			<option value="T_ADVERBIO">ADVERBIO</option>
			<option value="T_PREPOSICION">PREPOSICION</option>
			<option value="T_VERBO">VERBO</option>
		</select>
	</li>
<?php endforeach ?>
</ul>
   	<button type="submit">Aprender!</button>
   	<button onclick="window.history.back();">Regresar!</button>
</form>
<?php else: ?>
	<p>No hay palabras desconocidas, posiblemente no reconocí alguna frase por mi desconocimiento en la sintaxis del español</p>
   	<button onclick="window.history.back();">Regresar!</button>
<?php endif ?>
</body>
</html>