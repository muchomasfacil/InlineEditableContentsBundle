<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Form\Content;

use
    Symfony\Component\Form\AbstractType
    ,Symfony\Component\Form\FormBuilder
    ;

class MultiLinePlainTextType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'textarea');
    }

    public function getName()
    {
        return 'multi_line_plain_text';
    }
}
