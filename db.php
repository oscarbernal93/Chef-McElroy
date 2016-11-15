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
}

?>