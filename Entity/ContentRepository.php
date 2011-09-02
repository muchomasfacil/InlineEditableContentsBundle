<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * ContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends EntityRepository
{

    public function findOrCreateIfNotExist($handler, $entity_name = 'PlainText', $collection_length = 1, $default = array())
    {
        $em = $this->getEntityManager();
        $content = $em->find('MuchoMasFacilInlineEditableContentsBundle:Content', $handler);
        if (!$content){
            $content = new Content();
            //set handler
            $content->setHandler($handler);

            //set yml_params
            if (isset($default['yml_params'])) {
                $content->setYmlParams($default['yml_params']);
            }
            else {
                $content->setYmlParams('{}');
            }

            //set editor roles
            if (isset($default['yml_editor_roles'])) {
                $content->setYmlEditorRoles($default['yml_editor_roles']);
            }
            else {
                $content->setYmlEditorRoles('[ROLE_USER]');
            }

            //set admin roles
            if (isset($default['yml_admin_roles'])) {
                $content->setYmlAdminRoles($default['yml_admin_roles']);
            }
            else {
                $content->setYmlAdminRoles('[ROLE_ADMIN]');
            }

            //set entity_class
            if (isset($default['entity_class'])) {
                $content->setEntityClass($default['entity_class']);
            }
            else {
                $content->setEntityClass('\\MuchoMasFacil\\InlineEditableContentsBundle\\Entity\\Content\\'.$entity_name);
            }

            //set form_class
            if (isset($default['form_class'])) {
                $content->setFormClass($default['form_class']);
            }
            else {
                $content->setFormClass('\\MuchoMasFacil\\InlineEditableContentsBundle\\Form\\Content\\'.$entity_name.'Type');
            }

            //set form_class
            if (isset($default['render_template'])) {
                $content->setRenderTemplate($default['render_template']);
            }
            else {
                $content->setRenderTemplate('MuchoMasFacilInlineEditableContentsBundle:Content:Render/'.$entity_name.'.html.twig');
            }


            //set collection_length
            $content->setCollectionLength($collection_length);

            //set content
            if (isset($default['content']))  {
                if (!is_array($default['content'])) {
                    $default['content'] = array($default['content']);
                }
                $content->setContent($default['content']);
            }
            else {
                $entity_class = $content->getEntityClass();
                $content_content = new $entity_class();
                if (is_null($content->getCollectionLength()))  {
                    $content->setContent($content_content->getLoremIpsum(rand(2, 6)));
                }
                else {
                    $content->setContent($content_content->getLoremIpsum($content->getCollectionLength()));//one single by default
                }
            }

            $em->persist($content);
            $em->flush();

        }//!Content
        return $content;
    }


}