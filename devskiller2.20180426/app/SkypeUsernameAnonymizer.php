<?php

/**
 * Class SkypeUsernameAnonymizer
 *
 * Sample usages (parametrized):
 *
 * skype:john.doe -> skype:XXX
 * <a href="skype:john.doe?call">call</a> -> <a href="skype:ZZZ?call">call</a>
 */
class SkypeUsernameAnonymizer implements Anonymizer
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
        //return  preg_replace('/^skype:[a-z,A-Z,0-9]+?$/', 'skype:#?', $text);
        return preg_replace('/skype:[a-zA-z0-9]+\?/', 'skype:#?', $text);
    }
}