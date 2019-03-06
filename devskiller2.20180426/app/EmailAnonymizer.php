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
    private $_replacement;
    
    /**
     * PhoneNumberAnonymizer constructor.
     * @param string $replacement
     */
    public function __construct($replacement)
    {
        $this->_replacement = $replacement;
    }

    /**
     *
     * @param $text
     *
     * @return string
     */
    public function anonymize($text)
    {
        // -todo- extract rules from database

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
}
