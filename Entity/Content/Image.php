<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Entity\Content;

use MuchoMasFacil\InlineEditableContentsBundle\Util\LoremIpsumGenerator;

class Image
{

    private $file_list;

    public function setFileList($file_list)
    {
        $this->file_list = $file_list;
    }

    public function getFileList()
    {
        return $this->file_list;
    }

    public function getLoremIpsum($count = 1, $number_of_words_to_lorem_ipssum = null)
    {
        $return = array();
        $lorem_ipsum = new LoremIpsumGenerator(50); //words per paragraph
        if ((!is_int($count)) || ($count < 0)) {
            $count = 1;
        }
        if ((!is_int($number_of_words_to_lorem_ipssum)) || ($number_of_words_to_lorem_ipssum < 0)) {
             $number_of_words_to_lorem_ipssum = rand(1, 4);
        }
        for ($i = 0; $i < $count; $i++)
        {
            $entry = new self;
            $entry->setFileList('http://www.muchomasfacil.com/images/logo.png, http://www.muchomasfacil.com/images/logo.png');
            $return[] = $entry;
        }
        return $return;
    }

    public function __toString()
    {
        $cut_in = 60;
        $content = strip_tags($this->getFileList());
        return (strlen($content) > $cut_in) ? substr($content, 0, $cut_in).'...' : $content ;

    }
}
