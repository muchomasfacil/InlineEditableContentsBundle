<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Entity;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\DependencyInjection\ContainerAware;

/**
 * ContentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ContentRepository extends EntityRepository
{

    private $widgets_default_params = array();

    public function setWidgetsDefaultParams($params)
    {
        $this->widgets_default_params = $params;
    }

    public function findOrCreateIfNotExist($handler, $widget = 'default', $collection_length = 1, $custom_params = array())
    {
        $params = $this->widgets_default_params['default']; //default must be defined in services.yml

        if (($widget != 'default') && (isset($this->widgets_default_params[$widget]))) {
            $params = array_merge($params, $this->widgets_default_params[$widget]);
        }
        $params = array_merge($params, $custom_params);

        $em = $this->getEntityManager();

        $content = $em->find('MuchoMasFacilInlineEditableContentsBundle:Content', $handler);

        if (!$content){
            $content = new Content();
            //set handler
            $content->setHandler($handler);

            //set params from defaults
            $content->setYmlParams($params['yml_params']);
            $content->setYmlEditorRoles($params['yml_editor_roles']);
            $content->setYmlAdminRoles($params['yml_admin_roles']);
            $content->setEntityClass($params['entity_class']);
            $content->setFormClass($params['form_class']);
            $content->setRenderTemplate($params['render_template']);
            $content->setFormTemplate($params['form_template']);

            //set collection_length
            $content->setCollectionLength($collection_length);

            //set content
            if (isset($params['content']))  {
                if (!is_array($params['content'])) {
                    $params['content'] = array($params['content']);
                }
                $content->setContent($params['content']);
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
