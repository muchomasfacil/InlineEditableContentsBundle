<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Form\Content;

use
    Symfony\Component\Form\AbstractType
    ,Symfony\Component\Form\FormBuilder
    ;

class AdvancedImageType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('url', 'text', array(
              'attr' => array('data-mmf-fm'=> 'true')
            ))
            ->add('label', 'text')
            ->add('link', 'text')
        ;
    }

    public function getName()
    {
        return 'advanced_image';
    }
}
