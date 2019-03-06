<?php

/**
 * Class EmailAnonymizer
 *
 * Sample usages (parametrized):
 *
 * aaa@aaa.com -> ...@aaa.com
 * aaa@aaa.aaa.com -> ...@aaa.aaa.com
 * a-a@a_a.com -> XXX@a_a.com
 */
class EmailAnonymizer implements Anonymizer
{
    /**
     * @var string
     */
    private $replacement;
    
    /**
     * PhoneNumberAnonymizer constructor.
     * @param string $replacement
     */
    public function __construct($replacement)
    {
        $this->replacement = $replacement;
    }
    
    /**
     * @param string $text
     * @return array of string
     */
    public function anonymize($text)
    {

        $html = $text;
        $needle = "@";
        $lastPos = 0;
        $positions = array();

        while (($lastPos = strpos($html, $needle, $lastPos))!== false) {
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);
        }


        return $text;
    }

    public function solveAll($problems) {
        foreach($problems as $problem) {
            switch($problem['type']) {
                case 'A': $this->solveA($problem);
                    break;
                case 'B': $this->solveB($problem);
                    break;
                case 'C': $this->solveC($problem);
                    break;
                case 'D': $this->solveD($problem);
                    break;
                case 'E': $this->solveE($problem);
                    break;
            }
        }
    }

    public function solveA($problem) {
        //code to solve type A problems
    }

    public function solveB($problem) {
        //code to solve type B problems
    }

    public function solveC($problem) {
        //code to solve type C problems
    }

    public function solveD($problem) {
        //code to solve type D problems
    }

    public function solveE($problem) {
        //code to solve type E problems
    }
}
