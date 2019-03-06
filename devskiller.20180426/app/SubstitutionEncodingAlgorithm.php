<?php

/**
 * Class SubstitutionEncodingAlgorithm
 */
class SubstitutionEncodingAlgorithm implements EncodingAlgorithm
{
    /**
     * @var array
     */
    private $substitutions;
    private $all;

    /**
     * SubstitutionEncodingAlgorithm constructor.
     * @param $substitutions
     */
    public function __construct(array $substitutions)
    {
        $this->substitutions = array();
        foreach ( $substitutions as $substitution) {
            if( strlen($substitution) == 2 ) {
                $this->substitutions[] = $substitution;
                $this->all.=$substitution;

                $this->substitutions[] =  strtoupper($substitution);
                $this->all.=strtoupper($substitution);
            }
        }

        //$this->substitutions = $substitutions;
    }

    /**
     * Encodes text by substituting character with another one provided in the pair.
     * For example pair "ab" defines all "a" chars will be replaced with "b" and all "b" chars will be replaced with "a"
     * Examples:
     *      substitutions = ["ab"], input = "aabbcc", output = "bbaacc"
     *      substitutions = ["ab", "cd"], input = "adam", output = "bcbm"
     *
     * @param string $text
     * @return string
     */
    public function encode($text)
    {
        $length = strlen($text);
        $encodedText = array();
        for ($i=0; $i<$length; $i++) {
            $position = strpos( $this->all,$text[$i] );
            if( false!==$position ) {
                $substitution = floor( $position / 2 );
                $encodedText[$i] = str_replace( $text[$i],'', $this->substitutions[ $substitution ] );
            } else {
                $encodedText[$i] = $text[$i];
            }

            /*
            $position = strpos( self::CHARACTERS, $text[$i] );
            if( false!==$position ) {
                $position+=$this->offset;
                $position%=strlen(self::CHARACTERS);
                $encodedText[$i] = self::CHARACTERS[$position];
            } else {
                $encodedText[$i] = $text[$i];
            }
            */
        }

        $encodedText = implode("",$encodedText);
        return $encodedText;
    }
}