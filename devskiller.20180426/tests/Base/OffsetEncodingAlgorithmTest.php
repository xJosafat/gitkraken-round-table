<?php

namespace Base;

/**
 * Class OffsetEncodingAlgorithmTest
 * @package Base
 */
class OffsetEncodingAlgorithmTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider getTexts
     * @param $offset
     * @param $text
     * @param $encoded
     */
    public function testValidEncoding($offset, $text, $encoded)
    {
        $algorithm = new \OffsetEncodingAlgorithm($offset);

        $this->assertEquals($encoded, $algorithm->encode($text));
    }

    /**
     * @return array
     */
    public function getTexts()
    {
        return array(
            array(0, '', ''),
            array(1, '', ''),
            array(1, 'a', 'b'), //2
            array(0, 'abc def.', 'abc def.'),
            array(1, 'abc def.', 'bcd efg.'),
            array(2, 'z', 'B'), //5
            array(1, 'Z', 'a'),
            array(26, 'abc def.', 'ABC DEF.'), //7
            array(26, 'ABC DEF.', 'abc def.'), //8
        );
    }
}