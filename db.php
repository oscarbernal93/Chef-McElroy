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
	public function fruta($nombre)
	{
		$sql = "SELECT fruta.id ,fruta.nombre FROM fruta WHERE nombre LIKE '$nombre'";
		$sentencia = $this->pdo->query($sql);
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$resu = $filas[0];
		if (is_null($resu)) {
			# como no existe la crea
			$sql = "INSERT INTO fruta (nombre) VALUES ('$nombre')";
			$sentencia = $this->pdo->prepare($sql);
			$sentencia->execute();
			$sql = "SELECT fruta.id ,fruta.nombre FROM fruta WHERE nombre LIKE '$nombre'";
			$sentencia = $this->pdo->query($sql);
			$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
			$resu = $filas[0];
		}
		return $resu;
	}
	public function caracteristica($nombre)
	{
		$sql = "SELECT caracteristica.id ,caracteristica.nombre FROM caracteristica WHERE nombre LIKE '$nombre'";
		$sentencia = $this->pdo->query($sql);
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		$resu = $filas[0];
		if (is_null($resu)) {
			# como no existe la crea
			$sql = "INSERT INTO caracteristica (nombre) VALUES ('$nombre')";
			$sentencia = $this->pdo->prepare($sql);
			$sentencia->execute();
			$sql = "SELECT caracteristica.id ,caracteristica.nombre FROM caracteristica WHERE nombre LIKE '$nombre'";
			$sentencia = $this->pdo->query($sql);
			$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
			$resu = $filas[0];
		}
		return $resu;

	}
	public function propiedad($fruta,$caracter)
	{
		$sql = "SELECT valor FROM fruta_caracteristica WHERE fruta_id = $fruta AND caracteristica_id = $caracter";
		$sentencia = $this->pdo->query($sql);
		$filas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
		return $filas[0];

	}
	public function agregar_propiedad($id_f,$id_c,$valor)
	{
		$sql = "INSERT INTO fruta_caracteristica (fruta_id,caracteristica_id,valor) VALUES ('$id_f','$id_c','$valor')";
		$sentencia = $this->pdo->prepare($sql);
		$sentencia->execute();
	}
	public function editar_propiedad($id_f,$id_c,$valor)
	{
		$sql = "UPDATE fruta_caracteristica SET valor=$valor WHERE fruta_id = $id_f AND caracteristica_id = $id_c";
		$sentencia = $this->pdo->prepare($sql);
		$sentencia->execute();
	}
	public function palabrear($palabra,$token,$valor=NULL)
	{
		if ($token == 'T_ADVERBIO' and !is_null($valor)) {
			# caso especial
			$tabla = 'adverbio';
			$sql = "INSERT INTO $tabla (cadena,valor) VALUES ('$palabra','$valor')";
		}else{
			$tabla = strtolower(explode('_', $token)[1]);
			$sql = "INSERT INTO $tabla (cadena) VALUES ('$palabra')";
		}
		$sentencia = $this->pdo->prepare($sql);
		$sentencia->execute();
	}
}

?>