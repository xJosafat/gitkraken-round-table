<?php

/**
 * Class OffsetEncodingAlgorithm
 */
class OffsetEncodingAlgorithm implements EncodingAlgorithm
{
    /**
     * Lookup string
     */
    const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @var int
     */
    private $offset;

    /**
     * @param int $offset
     */
    public function __construct($offset = 13)
    {
        $this->offset = $offset;
    }

    /**
     * Encodes text by shifting each character (existing in the lookup string) by an offset (provided in the constructor)
     * Examples:
     *      offset = 1, input = "a", output = "b"
     *      offset = 2, input = "z", output = "B"
     *      offset = 1, input = "Z", output = "a"
     *
     * @param string $text
     * @return string
     */
    public function encode($text)
    {
        $length = strlen($text);
        $encodedText = array();
        for ($i=0; $i<$length; $i++) {

            $position = strpos( self::CHARACTERS, $text[$i] );
            if( false!==$position ) {
                $position+=$this->offset;
                $position%=strlen(self::CHARACTERS);
                $encodedText[$i] = self::CHARACTERS[$position];
            } else {
                $encodedText[$i] = $text[$i];
            }
        }

        $encodedText = implode("",$encodedText);

        return $encodedText;
    }
}