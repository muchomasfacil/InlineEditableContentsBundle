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
        //$this->container, pasarlo a findOrCreateIfNotExist

        $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->setWidgetsDefaultParams($this->container->getParameter('mucho_mas_facil_inline_editable_contents.widgets'));

        $content = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->find('plain-text-example');
        if ($content) {
            $em->remove($content);
            $em->flush();
        }
        $contents['plain-text-example'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('plain-text-example');//findOrCreateIfNotExist

        $content = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->find('multi-line-plain-text-example');
        if ($content) {
            $em->remove($content);
            $em->flush();
        }
        $contents['multi-line-plain-text-example'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('multi-line-plain-text-example', 'multi_line_plain_text');

        $content = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->find('rich-text-collection-example');
        if ($content) {
            $em->remove($content);
            $em->flush();
        }
        $contents['rich-text-collection-example'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('rich-text-collection-example', 'rich_text', null);

        $content = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->find('custom-rich-text-example');
        if ($content) {
            $em->remove($content);
            $em->flush();
        }
        $custom_params = array();
                         /*array(
            'yml_params' => '
ckeditor_load_option: custom
ckeditor_custom_options: |
    //esto es la prueba
    '
        );*/
        $contents['custom-rich-text-example'] = $em->getRepository('MuchoMasFacilInlineEditableContentsBundle:Content')->findOrCreateIfNotExist('custom-rich-text-example', 'custom_rich_text', 1, $custom_params);

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
