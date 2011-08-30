<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MuchoMasFacil\InlineEditableContentsBundle\Entity\Content\PlainText;
use MuchoMasFacil\InlineEditableContentsBundle\Form\Content\PlainTextType;

class ContentController extends Controller
{

    private $render_vars = array();

    function __construct()
    {
        $this->render_vars['bundle_name'] = 'MuchoMasFacilInlineEditableContentsBundle';
        $this->render_vars['controller_name'] = str_replace('Controller', '', str_replace(__NAMESPACE__.'\\', '', __CLASS__));
    }

    private function getTemplateNameByDefaults($action_name, $template_format = 'html')
    {
      $this->render_vars['action_name'] = str_replace('Action', '', $action_name);
      return $this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':' . $this->render_vars['action_name'] . '.'.$template_format.'.twig';
    }

    public function renderAction($handler)
    {
        $em = $this->get('doctrine')->getEntityManager();
        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
        $params = $content->getParsedParams();

        $this->render_vars['content'] = $content;
        return $this->render($content->getRenderTemplate(), $this->render_vars);
    }

    public function collectionAction($handler, $content_container_id, $reload_container = false)
    {
        $em = $this->get('doctrine')->getEntityManager();

        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);

        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'xml'), $this->render_vars);
    }

    public function sortCollectionAction($handler, $content_container_id, $reload_container = false)
    {
        $em = $this->get('doctrine')->getEntityManager();

        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
        $content_content = $content->getContent();

        $li_sortable = $this->get('request')->get('mmf-iec-li-sortable');
        $new_content = array();
        foreach ($li_sortable as $value){
            $new_content[] =  $content_content[$value];
        }

        $content->setContent(array_values($new_content));
        $em->persist($content);
        $em->flush();

        $flash_messages[] = array('highlight' => $this->get('translator')->trans('The entry was moved successfully', array(), 'iec'));

        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;
        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':collection.xml.twig', $this->render_vars);
    }

    public function addSingleContentAction($handler, $content_container_id, $content_order)
    {
        try
        {
            $em = $this->get('doctrine')->getEntityManager();

            $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
            $content_content = $content->getContent();
            if ((!is_null($content->getCollectionLength())) && (count($content_content) >= $content->getCollectionLength())) {
                $flash_messages[] = array('error' => $this->get('translator')->trans('Overpassed maximum number of items for this content', array(), 'iec'). ': '.$content->getCollectionLength());
                $reload_container = false;
            }
            else{
                $class_name = $content->getEntityClass();
                $new_entry = new $class_name();
                $content_to_insert = $new_entry->getLoremIpsum();
                if (isset($content_content[$content_order])){
                    $content_to_insert[] = $content_content[$content_order];
                    array_splice($content_content, $content_order, 1, $content_to_insert);
                }
                else
                {
                    $content_content[] = $content_to_insert[0];
                }
                $reload_container = true;
                $content->setContent(array_values($content_content));
                $em->persist($content);
                $em->flush();
                $flash_messages[] = array('highlight' => $this->get('translator')->trans('The new entry was created successfully', array(), 'iec'));
            }
        }
        catch (Exception $e){
            $flash_messages[] = array('error' => $this->get('translator')->trans('There was a problem creating the new entry', array(), 'iec'));
            $reload_container = false;
        }

        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;
        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':collection.xml.twig', $this->render_vars);
    }

    public function deleteContentAction($handler, $content_container_id, $content_order)
    {
        $em = $this->get('doctrine')->getEntityManager();

        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
        $content_content = $content->getContent();

        if (isset($content_content[$content_order]))
        {
            if (isset($content_content[$content_order])){
                unset($content_content[$content_order]);
                $content->setContent(array_values($content_content));
                $flash_messages[] = array('highlight' => $this->get('translator')->trans('The entry was deleted successfully', array(), 'iec'));
                $reload_container = true;
            }
            else{
                $flash_messages[] = array('error' => $this->get('translator')->trans('The entry did not exist. Nothing deleted', array(), 'iec'));
                $reload_container = false;
            }
        }

        if ($content_order == 'all'){
            $content->setContent(null);
            $flash_messages[] = array('highlight' => $this->get('translator')->trans('All entries were deleted successfully', array(), 'iec'));
            $reload_container = true;
        }

        $em->persist($content);
        $em->flush();

        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;
        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':collection.xml.twig', $this->render_vars);
    }

    public function editorFormAction($handler, $content_container_id, $content_order = 0, $action_if_success = false)
    {
        $request = $this->get('request');
        $em = $this->get('doctrine')->getEntityManager();

        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
        $content_content = $content->getContent();
        $single_content = $content_content[$content_order];
        //$logger = $this->get('logger')->info('hola' . get_class($single_content));
        $type_class = $content->getFormClass();
        $form = $this->get('form.factory')->create(new $type_class(), $single_content);
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $content_content[$content_order] = $single_content;
                //double flush with null entry to avoid the unitofwork not updating the array
                $content->setContent(null);
                $em->persist($content);
                $em->flush();
                $content->setContent(array_values($content_content));
                $em->persist($content);
                $em->flush();

                $flash_messages[] = array('highlight' => $this->get('translator')->trans('The entry was saved successfully', array(), 'iec'));
                $reload_container = true;
            }
            else{
                $flash_messages[] = array('error' => $this->get('translator')->trans('Correct form data', array(), 'iec'));
                $action_if_success = false;
            }
        }

        //if (isset($flash_messages)) $this->get('session')->setFlash('flash_messages', $flash_messages);
        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;

        $this->render_vars['form'] = $form->createView();
        $this->render_vars['content_order'] = $content_order;
        $this->render_vars['action_if_success'] = $action_if_success;

        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = (isset($reload_container))? $reload_container: false;
        $this->render_vars['content'] = $content;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'xml'), $this->render_vars);
    }

}
