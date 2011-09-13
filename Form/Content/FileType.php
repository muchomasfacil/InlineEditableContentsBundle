<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Form\Content;

use
    Symfony\Component\Form\AbstractType
    ,Symfony\Component\Form\FormBuilder
    ;

class FileType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'file', , array(
              'attr' => array('data-mmf-fm'=> 'default')
            )););
    }

    public function getName()
    {
        return 'file_type';
    }
}

