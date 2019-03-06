<?php

/**
 * Class PhoneNumberAnonymizer
 *
 * Sample usages (parametrized):
 *
 * +48 666 777 888 -> +48 666 777 XXX
 * +246 666 777 888 -> +48 666 7XX XXX
 * +246 666 777 888 -> +48 666 777 88*
 * +246 666 777 888 -> +48 xxx xxx xxx
 */
class PhoneNumberAnonymizer
{
    /**
     * @var string
     */
    private $replacement;

    /**
     * @var int
     */
    private $lastDigits;

    /**
     * PhoneNumberAnonymizer constructor.
     * @param string $replacement
     * @param int $lastDigits
     */
    public function __construct($replacement, $lastDigits = 3)
    {
        $this->replacement = $replacement;
        $this->lastDigits = $lastDigits;
    }

    /**
     * @param string $text
     * @return array of string
     */
    public function anonymize($text)
    {
        $encodedText = preg_replace("/[^0-9]/", $this->replacement, $text,$this->lastDigits);

        // @todo: Implement it
        return $text;
    }
}