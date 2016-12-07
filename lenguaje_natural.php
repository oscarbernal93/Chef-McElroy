<?php
include_once 'db.php';

/**
* El Lector
*/
class Lector
{
    protected $frase;
    protected $indice;
    protected $tamano;
    function __construct($elementos)
    {
        $this->frase = $elementos;
        $this->indice = 0;
        $this->tamano = count($elementos);
    }
    //retorna la palabra que sigue
    public function siguiente_palabra()
    {
        //verifica que existan palabras
        if ($this->indice < $this->tamano) {
            $palabra = $this->frase[$this->indice];
            $this->indice++;
            //verifica que la palabra no sea un espacio
            if ('T_ESPACIO' == $palabra[1]) {
               return $this->siguiente_palabra();
            }else{
                return $palabra;
            }
        }else{
            return NULL; //fin de palabra
        }
    }
    //devuelve como frase (array de duplas) lo que hay
    //desde la posicion actual en adelante
    public function restos()
    {
        return array_slice($this->frase, $this->indice);
    }
    //esta funcion devuelve la frase de lo que se ha leido
    public function leido()
    {
        return array_slice($this->frase,0,$this->indice);
    }
    //esta funcion devuelve como cadena la frase
    public function frase()
    {
        $cadena = "";
        foreach ($this->frase as $palabra) {
            $cadena.=$palabra[0];
        }
        return $cadena;
    }
    //compara la palabra con el token
    public function match($token)
    {
        //verifica que existan palabras
        if ($this->indice < $this->tamano) {
            $palabra = $this->frase[$this->indice];
            $this->indice++;
            //verifica que la palabra no sea un espacio
            if ('T_ESPACIO' == $palabra[1]) {
               return $this->match($token);
            }else{
                if ($token == $palabra[1]) {
                    return true;
                }else{
                    return false;
                }
            }
        }else{
            //si no hay mas palabras
            //verifica si se espera final de palabra
            if ('T_EOF' == $token) {
                return true;
            }
            return false; //fin de palabra
        }
    }
    //reinicia el puntero
    public function reset(){$this->indice = 0;}
    public function rewind($n=1){$this->indice-=$n;}

}
/**
* El interprete se encarga de entender lo que el usurio dice
*/
class Interprete
{
    protected $regex;
    protected $offsetToToken;
    protected $propiedades;

    public function __construct() {
        $database = new Conocimiento;
        $this->propiedades = array();
        $adv = $database->obtener_propiedad("adverbio");
        $adj = $database->obtener_propiedad("adjetivo");
        $art = $database->obtener_propiedad("articulo");
        $prep = $database->obtener_propiedad("preposicion");
        $sust = $database->obtener_propiedad("sustantivo");
        $verb = $database->obtener_propiedad("verbo");

        //elementos lexicos desde la base de conocimiento
        $elementos = array();
        $elementos[$adj]='T_ADJETIVO';
        $elementos[$art]='T_ARTICULO';
        $elementos[$sust]='T_SUSTANTIVO';
        $elementos[$adv]='T_ADVERBIO';
        $elementos[$prep]='T_PREPOSICION';
        $elementos[$verb]='T_VERBO';
        //otros elementos lexicos
        $elementos['\s+|-']= 'T_ESPACIO';
        $elementos['y|e|pero|,|.']= 'T_CONECTOR';
        $elementos['\w+']= 'T_PALABRA_DESCONOCIDA';

        $this->regex = '((' . implode(')|(', array_keys($elementos)) . '))A';
        $this->offsetToToken = array_values($elementos);
    }

    //esta funcion permite acceder a las propiedades
    public function propiedades()
    {
        //este array tiene como llave el nombre de la propiedad
        //y como valor, el valor discreto de cada propiedad
        return $this->propiedades;
    }

    /**
    * ANALIZADOR LEXICO
    * Se encarga de la Identificacion de las palabras
    */

    public function lex($string) {
        $tokens = array();

        $offset = 0;
        while (isset($string[$offset])) {
            if (!preg_match($this->regex, $string, $matches, null, $offset)) {
                echo "No esperaba: \"".$string[$offset]."\"";
                echo " en la palabra: '".$string."'";
                echo " posicion: ".$offset;
                die("lex_err");
            }

            // find the first non-empty element (but skipping $matches[0]) using a quick for loop
            for ($i = 1; '' === $matches[$i]; ++$i);

            $tokens[] = array($matches[0], $this->offsetToToken[$i - 1]);

            $offset += strlen($matches[0]);
        }

        return $tokens;
    }

    /**
    * ANALIZADOR SINTACTICO
    * Esta funcion identifica las frases
    * y las almacena en propiedades (a travez de las funciones tipo)
    */
    public function sintax($elementos)
    {
        $hannibal = new Lector($elementos);
        if ($this->sentencia($hannibal)) {
            return "valido";
        }else{
            return "invalido";
        }
        
    }
    //funcion sintactica
    public function sentencia($lector)
    {
        //se valida la primera regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_a($lector->leido()); return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_f($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_c($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_c($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_d($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_b($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->cosa($lector);
        $s &= $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_b($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_b($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->caracter($lector);
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_c($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $lector->match('T_ADJETIVO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_d($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_c($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_c($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_e($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_a($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_b($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_b($lector->leido());return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->caracter($lector);
        //el fin solo se debe validar
        //si lo que se lleva es verdadero
        if($s){$s &= $this->fin($lector);}
        if($s){$this->tipo_a($lector->leido());return true;}


        //si ninguna regla se cumple
        return false;

    }
    //funcion sintactica
    public function caracter($lector)
    {
        $s = $lector->match('T_ADJETIVO');
        if($s){return true;}
        $lector->rewind();
        //siguiente regla
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADJETIVO');
        if($s){return true;}
        $lector->rewind(3);
        //siguiente regla
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADJETIVO');
        if($s){return true;}
        return false;
    }
    //funcion sintactica
    public function cosa($lector)
    {
        $s = $lector->match('T_SUSTANTIVO');
        if($s){return true;}
        $lector->rewind();
        //siguiente regla
        $s = $lector->match('T_ARTICULO');
        $s &= $lector->match('T_SUSTANTIVO');
        if($s){return true;}
        return false;
    }
    //funcion sintactica
    public function fin($lector)
    {
        //si la palabra termina es valido
        $s = $lector->match('T_EOF');
        if($s){return true;}
        $lector->rewind();
        //si no sigue un conector se sale
        $s = $lector->match('T_CONECTOR');
        if(!$s){return false;}
        //si no se ha salido es por que no es el fin de la palabra
        //y hay un conector valido
        //crea un nuevo lector con el resto de la frase
        $frase = $lector->restos();
        $lector->rewind();
        $lector = new Lector($frase);
        $s = $this->sentencia($lector);
        return $s;
    }
    //funciones de interpretacion
    public function tipo_a($frase)
    {
        //Propiedad de tipo A
        //Caracteristica asociada directamente a la fruta
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        //echo "\ntipo_a:".$hannibal->frase();
        $propiedad = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        } while (!is_null($palabra));
        $this->propiedades[$propiedad] *= $valor;
    }
    public function tipo_b($frase)
    {
        //Propiedad de tipo B
        //Caracteristica de crecimiento
        //crece en un arbol o es de cierto clima, o de cierto color
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        $propiedad = "";
        $sustantivo = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_SUSTANTIVO':
                    $sustantivo = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        }while (!is_null($palabra));
        //comparacion si contiene la palabra color
        //unico caso especial de este tipo
        //para los colores no se neceita guardar el sustantivo
        if (!(stripos($sustantivo, 'color') === false)) {
            $this->propiedades[$propiedad] *= $valor;
            return;
        }
        //si la propiedad esta vacia no pone la union
        if($propiedad != ""){$propiedad .= "_";}
        $this->propiedades[$propiedad.$sustantivo] *= $valor;
    }
    public function tipo_c($frase)
    {
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        echo "\ntipo_c:".$hannibal->frase();
        $propiedad = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        } while (!is_null($palabra));
    }
    public function tipo_d($frase)
    {
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        echo "\ntipo_d:".$hannibal->frase();
        $propiedad = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        } while (!is_null($palabra));
    }
    public function tipo_e($frase)
    {
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        echo "\ntipo_e:".$hannibal->frase();
        $propiedad = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        } while (!is_null($palabra));
    }
    public function tipo_f($frase)
    {
        $database = new Conocimiento;
        $hannibal = new Lector($frase);
        echo "\ntipo_f:".$hannibal->frase();
        $propiedad = "";
        $valor = 1;
        //comienza con 1, osea que tiene la totalidad de la propiedad
        do {
            $palabra = $hannibal->siguiente_palabra();
            switch ($palabra[1]) {
                case 'T_ADJETIVO':
                    $propiedad = $palabra[0];
                    break;
                case 'T_ADVERBIO':
                    $multiplicador = $database->obtener_valor($palabra[0]);
                    $valor *= $multiplicador;
                    break;
            }
        } while (!is_null($palabra));
    }

}


?>