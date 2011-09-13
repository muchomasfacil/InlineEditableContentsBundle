<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DemoController extends Controller
{

    public function indexAction()
    {
        /*if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }*/

        $em = $this->get('doctrine')->getEntityManager();
        $contents['page-h1'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('page-h1', 'PlainText', 1,
                array(
                    //'yml_params' => array(
                            // TODO testar esta funcionalidad
                            //'form_template' => 'CustomBundle:customaController:custom.html.twig'
                            //'dialog_title' => TODO
                        //)
                    //'yml_editor_roles' =>
                    //'yml_admin_roles' =>
                    //'entity_class' => "MuchoMasFacil\InlineEditableContentsBundle\Entity\Content\Custom"
                    //'form_class' => "MuchoMasFacil\InlineEditableContentsBundle\Form\Content\CustomType"
                    //'render_template' => 'CustomBundle:customaController:custom.html.twig'
                    //'content' => array('hola', 'radiola') ó 'loquesea'
                    )
                );//findOrCreateIfNotExist
        $contents['page-html'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('page-html', 'RichText', null);

        $contents['img-img'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('img-img', 'PlainText', 1,
            array(
              'form_class' => "MuchoMasFacil\InlineEditableContentsBundle\Form\Content\FileType"
            )
        );

        return $this->render('MuchoMasFacilInlineEditableContentsBundle:Demo:index.html.twig', array('contents' => $contents));
    }

    public function loginAction()
    {
        $this->redirect($this->getUrl('demo'));
    }

    public function loginOut()
    {

        $this->redirect($this->getUrl('demo'));
    }

}

