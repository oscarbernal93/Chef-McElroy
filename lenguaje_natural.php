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

    public function __construct() {
        $database = new Conocimiento;
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
        $elementos['\s+|\r|\n|-']= 'T_ESPACIO';
        $elementos['y|e|pero|,']= 'T_CONECTOR';
        $elementos['\w+']= 'T_PALABRA_DESCONOCIDA';

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
        $s &= $this->fin($lector);
        if($s){echo "#a1 "; return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#f1 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#c1 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->fin($lector);
        if($s){echo "#c2 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#d1 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#b1 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#e1 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->cosa($lector);
        $s &= $lector->match('T_VERBO');
        $s &= $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#e2 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#b2 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#b3 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->caracter($lector);
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->fin($lector);
        if($s){echo "#c3 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#e3 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#e4 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $lector->match('T_ADJETIVO');
        $s &= $this->fin($lector);
        if($s){echo "#e5 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#d2 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->fin($lector);
        if($s){echo "#c4 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $lector->match('T_ADVERBIO');
        $s &= $this->fin($lector);
        if($s){echo "#c5 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $this->fin($lector);
        if($s){echo "#e6 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_ADJETIVO');
        $s &= $this->fin($lector);
        if($s){return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $lector->match('T_ADVERBIO');
        $s &= $lector->match('T_VERBO');
        $s &= $lector->match('T_PREPOSICION');
        $s &= $this->cosa($lector);
        $s &= $this->fin($lector);
        if($s){echo "#a2 ";return true;}
        //se valida la siguiente regla
        $lector->reset();
        $s = $this->caracter($lector);
        $s &= $this->fin($lector);
        if($s){echo "#a3 ";return true;}


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
        $lector = new Lector($frase);
        echo "~";
        $s = $this->sentencia($lector);
        return $s;
    }

}


?>