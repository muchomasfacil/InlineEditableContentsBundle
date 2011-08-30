<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Entity\Content;

use MuchoMasFacil\InlineEditableContentsBundle\Util\LoremIpsumGenerator;

class RichText
{

    private $content;

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getLoremIpsum($count = 1, $number_of_words_to_lorem_ipssum = null)
    {
        $return = array();
        $lorem_ipsum = new LoremIpsumGenerator(50); //words per paragraph
        if ((!is_int($count)) || ($count < 0)) {
            $count = 1;
        }
        if ((!is_int($number_of_words_to_lorem_ipssum)) || ($number_of_words_to_lorem_ipssum < 0)) {
             $number_of_words_to_lorem_ipssum = rand(60, 300);
        }
        for ($i = 0; $i < $count; $i++)
        {
            $entry = new self;
            $entry->setContent(trim($lorem_ipsum->getContent($number_of_words_to_lorem_ipssum))); //html by default
            $return[] = $entry;
        }
        return $return;
    }

    public function __toString()
    {
        $cut_in = 60;
        $content = strip_tags($this->content);
        return (strlen($content) > $cut_in) ? substr($content, 0, $cut_in).'...' : $content ;
    }
}
