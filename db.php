<?php 
// PDO
class Conocimiento
{
	private $pdo;
	function __construct(){
		$this->pdo = new PDO('mysql:host=127.0.0.1;dbname=chucho', 'root', '1234');
	}

	public function obtener_propiedad($tabla)
	{
		$sentencia = $this->pdo->query("SELECT cadena FROM ".$tabla);
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$cadena = "";
        foreach ($filas as $fila) {
            $cadena .= $fila["cadena"]."|";
        }
        $cadena = substr($cadena, 0,-1);
		return $cadena;
	}
	public function obtener_valor($adverbio)
	{
		$sentencia = $this->pdo->query("SELECT valor FROM adverbio WHERE cadena LIKE '$adverbio'");
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		return $filas[0]['valor'];
	}
	public function obtener_frutas($propiedades)
	{
		$sql = "SELECT fruta.nombre AS nombre,caracteristica.nombre AS caracter,valor FROM fruta JOIN caracteristica,fruta_caracteristica WHERE fruta.id = fruta_id AND caracteristica.id = caracteristica_id AND (";
		foreach ($propiedades as $propiedad => $valor) {
			$sql .= "caracteristica.nombre LIKE '$propiedad'";
            $sql .= " OR ";
        }
        $sql .= " FALSE )";
		$sentencia = $this->pdo->query($sql);
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		return $filas;
	}
}

?>