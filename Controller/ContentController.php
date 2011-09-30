<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use MuchoMasFacil\InlineEditableContentsBundle\Form\ContentType;

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

    private function trans($translatable, $params = array())
    {
      return $this->get('translator')->trans($translatable, $params, strtolower($this->render_vars['bundle_name']));
    }

    private function getContentByHandler($handler)
    {
        $em = $this->get('doctrine')->getEntityManager();
        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);
        if (!$content) {
            throw $this->createNotFoundException($this->trans('No content afound for handler'). ' '.$handler);
        }

        return $content;
    }
//------------------------------------------------------------------------------
//actions from here on
    public function indexAction($handler, $content_container_id)
    {
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        if ($content->getCollectionLength() == 1){
            $action_name = 'editorForm';
        }
        else {
            $action_name = 'collection';
        }
        return $this->forward($this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':'.$action_name, array('handler' => $handler, 'content_container_id' => $content_container_id));
    }


    public function renderAction($handler)
    {
        $content = $this->getContentByHandler($handler);

        $this->render_vars['content'] = $content;
        return $this->render($content->getRenderTemplate(), $this->render_vars);
    }

    public function renderWithContainerAction($handler, $container_html_tag = 'div', $container_html_attributes = '')
    {
        $content = $this->getContentByHandler($handler);
        $content_container_id = $container_html_tag . '-' . $content->getHandler();

        if (!is_null($this->get('security.context')->getToken())) {
            if ((true === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) || (true === $this->get('security.context')->isGranted($content->getParsedAdminRoles()))) {
                $container_html_attributes .= ' data-mmf-iec-editable-url="'.$this->get('router')->generate('mmf_iec_index', array('handler'=> $content->getHandler(), 'content_container_id'=> $content_container_id)).'"';
            }
        }
        $this->render_vars['content'] = $content;
        $this->render_vars['container_html_tag'] = $container_html_tag;
        $this->render_vars['container_html_attributes'] = $container_html_attributes;
        $this->render_vars['content_container_id'] = $content_container_id;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__), $this->render_vars);
    }

    public function collectionAction($handler, $content_container_id, $reload_container = false)
    {
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'xml'), $this->render_vars);
    }

    public function sortCollectionAction($handler, $content_container_id, $reload_container = false)
    {
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        $content_content = $content->getContent();
        $li_sortable = $this->get('request')->get('mmf-iec-li-sortable');
        $new_content = array();
        foreach ($li_sortable as $value){
            $new_content[] =  $content_content[$value];
        }

        $content->setContent(array_values($new_content));
        $em = $this->get('doctrine')->getEntityManager();
        $em->persist($content);
        $em->flush();

        $flash_messages[] = array('highlight' => $this->trans('The entry was moved successfully'));

        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;
        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = $reload_container;
        $this->render_vars['content'] = $content;
        return $this->render($this->render_vars['bundle_name'] . ':' . $this->render_vars['controller_name'] . ':collection.xml.twig', $this->render_vars);
    }

    public function addSingleContentAction($handler, $content_container_id, $content_order)
    {
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        try
        {
            $content_content = $content->getContent();
            if ((!is_null($content->getCollectionLength())) && (count($content_content) >= $content->getCollectionLength())) {
                $flash_messages[] = array('error' => $this->trans('Overpassed maximum number of items for this content'). ': '.$content->getCollectionLength());
                $reload_container = false;
            }
            else{
                $class_name = $content->getEntityClass();
                $new_entry = new $class_name();
                $content_to_insert = $new_entry->getLoremIpsum(1);
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
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($content);
                $em->flush();
                $flash_messages[] = array('highlight' => $this->trans('The new entry was created successfully'));
            }
        }
        catch (Exception $e){
            $flash_messages[] = array('error' => $this->trans('There was a problem creating the new entry'));
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
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        $content_content = $content->getContent();

        if (isset($content_content[$content_order]))
        {
            if (isset($content_content[$content_order])){
                unset($content_content[$content_order]);
                $content->setContent(array_values($content_content));
                $flash_messages[] = array('highlight' => $this->trans('The entry was deleted successfully'));
                $reload_container = true;
            }
            else{
                $flash_messages[] = array('error' => $this->trans('The entry did not exist. Nothing deleted'));
                $reload_container = false;
            }
        }

        if ($content_order == 'all'){
            $content->setContent(null);
            $flash_messages[] = array('highlight' => $this->trans('All entries were deleted successfully'));
            $reload_container = true;
        }
        $em = $this->get('doctrine')->getEntityManager();
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
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedEditorRoles())) {
            throw new AccessDeniedException();
        }

        $request = $this->get('request');

        $content_content = $content->getContent();
        if (!isset($content_content[$content_order]))
        {
            $entity_class = $content->getEntityClass();
            $content_content[$content_order] = new $entity_class();
        }
        $single_content = $content_content[$content_order];
        //$logger = $this->get('logger')->info('hola' . get_class($single_content));
        $type_class = $content->getFormClass();
        $form = $this->get('form.factory')->create(new $type_class(), $single_content);
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                $content_content[$content_order] = $single_content;
                //double flush with null entry to avoid the unitofwork not updating the array
                $content->setContent(null);
                $em->persist($content);
                $em->flush();
                $content->setContent(array_values($content_content));
                $em->persist($content);
                $em->flush();

                $flash_messages[] = array('highlight' => $this->trans('The entry was saved successfully'));
                $reload_container = true;
            }
            else{
                $flash_messages[] = array('error' => $this->trans('Correct form data'));
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

    public function adminFormAction($handler, $content_container_id)
    {
        $content = $this->getContentByHandler($handler);

        if (false === $this->get('security.context')->isGranted($content->getParsedAdminRoles())) {
            throw new AccessDeniedException();
        }
        $request = $this->get('request');

        $form = $this->get('form.factory')->create(new ContentType(), $content);
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $em = $this->get('doctrine')->getEntityManager();
                $em->persist($content);
                $em->flush();

                $flash_messages[] = array('highlight' => $this->trans('The entry was saved successfully'));
                $reload_container = true;
            }
            else{
                $flash_messages[] = array('error' => $this->trans('Correct form data'));
                $action_if_success = false;
            }
        }

        //if (isset($flash_messages)) $this->get('session')->setFlash('flash_messages', $flash_messages);
        if (isset($flash_messages)) $this->render_vars['flash_messages'] = $flash_messages;

        $this->render_vars['form'] = $form->createView();

        $this->render_vars['content_container_id'] = $content_container_id;
        $this->render_vars['reload_container'] = (isset($reload_container))? $reload_container: false;
        $this->render_vars['content'] = $content;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'xml'), $this->render_vars);
    }

    public function ckeditorConfigAction($handler, $input_id)
    {
        $em = $this->get('doctrine')->getEntityManager();
        $content = $em->getRepository($this->render_vars['bundle_name'] . ':Content')->find($handler);

        $ckeditor_options = $this->container->getParameter('mucho_mas_facil_inline_editable_contents.ckeditor_options');
        $ckeditor_config = $ckeditor_options['default'];
        if ($content) {
            $content_params = $content->getParsedParams();
            //die(print_r($content_params));
            if ((isset($content_params['ckeditor_load_option'])) && (isset($ckeditor_options[$content_params['ckeditor_load_option']]))){
                $ckeditor_config = $ckeditor_options[$content_params['ckeditor_load_option']];
            }
        }

        if(class_exists('MuchoMasFacil\FileManagerBundle\Util\CustomUrlSafeEncoder')) {
            $url_safe_encoder = new \MuchoMasFacil\FileManagerBundle\Util\CustomUrlSafeEncoder();
            $ckeditor_config .= "
            config.filebrowserBrowseUrl = '". $this->get('router')->generate('mmf_fm_with_layout', array('url_safe_encoded_params' => $url_safe_encoder->encode(array('upload_path_after_document_root'=> '/uploads/'.$content->getHandler().'/files/', 'load_options' => 'collection_any_file')) )) ."';
            config.filebrowserImageBrowseUrl = '". $this->get('router')->generate('mmf_fm_with_layout', array('url_safe_encoded_params' => $url_safe_encoder->encode(array('upload_path_after_document_root'=> '/uploads/'.$content->getHandler().'/images/', 'load_options' => 'collection_image')) )) ."';
            config.filebrowserFlashBrowseUrl = '". $this->get('router')->generate('mmf_fm_with_layout', array('url_safe_encoded_params' => $url_safe_encoder->encode(array('upload_path_after_document_root'=> '/uploads/'.$content->getHandler().'/swf/', 'load_options' => 'collection_swf')) )) ."';
            //config.filebrowserUploadUrl = '';
            //config.filebrowserImageUploadUrl = '';
            //config.filebrowserFlashUploadUrl = '';
            config.filebrowserWindowWidth = '700';
            config.filebrowserWindowHeight = '480';
            ";
        }
        if (isset($content_params['ckeditor_custom_options'])){
                $ckeditor_config .= $content_params['ckeditor_custom_options'];
            }
        $this->render_vars['ckeditor_config'] = $ckeditor_config;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'js'), $this->render_vars);
    }

    public function cacheClearAction()
    {
        $realCacheDir = $this->get('kernel')->getCacheDir();
        $oldCacheDir  = $realCacheDir.'_old';

        if (!is_writable($realCacheDir)) {
            $alert = $this->trans('Unable to write in the cache directory'). ': '.$realCacheDir;
        }
        else {
            rename($realCacheDir, $oldCacheDir);
            $this->get('filesystem')->remove($oldCacheDir);
            $alert = $this->trans('Cache deleted succesfully');
        }

        if (isset($alert)) $this->render_vars['alert'] = $alert;
        return $this->render($this->getTemplateNameByDefaults(__FUNCTION__, 'xml'), $this->render_vars);
    }

}

