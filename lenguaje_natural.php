<?php
/**
* El interprete se encarga de entender lo que el usurio dice
*/
class Interprete
{
    protected $regex;
    protected $offsetToToken;

    public function __construct(array $tokenMap) {
        $this->regex = '((' . implode(')|(', array_keys($tokenMap)) . '))A';
        $this->offsetToToken = array_values($tokenMap);
    }

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
}


?>