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
            return NULL;
        }
    }
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
        $v = $database->obtener_propiedad("verbo");
        $a = $database->obtener_propiedad("adverbio");
        $s = $database->obtener_propiedad("sustantivo");

        //elementos lexicos desde la base de conocimiento
        $elementos = array();
        $elementos[$v]='T_VERBO';
        $elementos[$a]='T_ADVERBIO';
        $elementos[$s]='T_SUSTANTIVO';
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
    }

}


?>