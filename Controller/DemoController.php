<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DemoController extends Controller
{

    public function indexAction()
    {
        $em = $this->get('doctrine')->getEntityManager();
        $contents['page-h1'] = array(
            'content_container_id' => 'h1-page-h1'
            ,'content' => $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('page-h1', 'PlainText', 1,
                array(
                    //'yml_params' => array(
                            // TODO testar esta funcionalidad
                            //'form_template' => 'CustomBundle:customaController:custom.html.twig'
                        //)
                    //'yml_editor_roles' =>
                    //'yml_admin_roles' =>
                    //entity_class => "MuchoMasFacil\InlineEditableContentsBundle\Entity\Content\Custom"
                    //form_class => "MuchoMasFacil\InlineEditableContentsBundle\Form\Content\CustomType"
                    //render_template => 'CustomBundle:customaController:custom.html.twig'
                    //'content' => array('hola', 'radiola') ó 'loquesea'
                    )
                )//findOrCreateIfNotExist
            );
        $contents['page-html'] = array(
            'content_container_id' => 'div-page-html'
            ,'content' => $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('page-html', 'RichText', null)
            );

        return $this->render('MuchoMasFacilInlineEditableContentsBundle:Demo:index.html.twig', array('contents' => $contents));
    }
}
