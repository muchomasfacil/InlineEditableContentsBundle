<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Form\Content;

use
    Symfony\Component\Form\AbstractType
    ,Symfony\Component\Form\FormBuilder
    ;

class RichTextType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('content', 'textarea', array(
              'attr' => array('data-mmf-iec-ckeditor'=> 'default')
            ));
    }

    public function getName()
    {
        return 'rich_text';
    }
}
