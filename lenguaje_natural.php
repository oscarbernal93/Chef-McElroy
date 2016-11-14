<?php
include_once 'db.php';

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
    * Este conjunto de funciones identifican las frases
    */

}


?>