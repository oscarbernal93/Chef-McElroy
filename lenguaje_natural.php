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
    public function rewind(){$this->indice--;}

}
/**
* El interprete se encarga de entender lo que el usurio dice
*/
class Interprete
{
    protected $regex;
    protected $offsetToToken;

    public function __construct() {
        $database = new Conocimiento;
        $verb = $database->obtener_propiedad("verbo");
        $adv = $database->obtener_propiedad("adverbio");
        $adj = $database->obtener_propiedad("adjetivo");
        $art = $database->obtener_propiedad("articulo");
        $prep = $database->obtener_propiedad("preposicion");
        $sust = $database->obtener_propiedad("sustantivo");

        //elementos lexicos desde la base de conocimiento
        $elementos = array();
        $elementos[$verb]='T_VERBO';
        $elementos[$adv]='T_ADVERBIO';
        $elementos[$adj]='T_ADJETIVO';
        $elementos[$art]='T_ARTICULO';
        $elementos[$prep]='T_PREPOSICION';
        $elementos[$sust]='T_SUSTANTIVO';
        //otros elementos lexicos
        $elementos['\w+']= 'T_PALABRA_DESCONOCIDA';
        $elementos['\s+']= 'T_ESPACIO';
        $elementos['y|e']= 'T_CONECTOR';

        $this->regex = '((' . implode(')|(', array_keys($elementos)) . '))A';
        $this->offsetToToken = array_values($elementos);
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
                die();
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
        $s &= $lector->match('T_EOF');
        if($s){return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_SUSTANTIVO');
        $s &= $lector->match('T_EOF');
        if($s){return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $lector->match('T_EOF');
        if($s){return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_EOF');
        if($s){return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_SUSTANTIVO');
        $s &= $lector->match('T_EOF');
        if($s){return true;}

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
        $s &= $lector->match('T_ADJETIVO');
        if($s){return true;}
        return false;
    }

}


?>