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
    private $wordsToReplace;
    
    /**
     * PhoneNumberAnonymizer constructor.
     * @param string $replacement
     */
    public function __construct($replacement)
    {
        $this->wordsToReplace = $replacement;
    }

    /**
     * @param string $text
     * @param int $times
     * @return array of string
     */
    public function anonymize($text, $times = 5)
    {
        // -todo- extract rules from database

        $html = $text;
        $needle = "@";
        $lastPos = 0;
        $positions = array();

        while (($lastPos = strpos($html, $needle, $lastPos))!== false && $times<=5) {
            $positions[] = $lastPos;
            $lastPos = $lastPos + strlen($needle);

            $times++;
        }


        return $text;
    }
}
