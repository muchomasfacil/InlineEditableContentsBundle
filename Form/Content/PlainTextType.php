<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Form\Content;

use
    Symfony\Component\Form\AbstractType
    ,Symfony\Component\Form\FormBuilder
    ;

class PlainTextType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'textarea');
    }

    public function getName()
    {
        return 'plain_text';
    }
}
