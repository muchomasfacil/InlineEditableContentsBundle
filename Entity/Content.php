<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Yaml\Yaml;

/**
 * MuchoMasFacil\InlineEditableContentsBundle\Entity\Content
 */
class Content
{
//------------------------------------------------------------------------------
//Custom code
//------------------------------------------------------------------------------
    public function getParsedAdminRoles()
    {
      return Yaml::parse($this->getYmlAdminRoles());
    }

    public function getParsedEditorRoles()
    {
      return Yaml::parse($this->getYmlEditorRoles());
    }

    public function getParsedParams()
    {
      return Yaml::parse($this->getYmlParams());
    }
//------------------------------------------------------------------------------


    /**
     * @var string $handler
     */
    private $handler;

    /**
     * @var text $yml_params
     */
    private $yml_params;

    /**
     * @var text $yml_editor_roles
     */
    private $yml_editor_roles;

    /**
     * @var text $yml_admin_roles
     */
    private $yml_admin_roles;

    /**
     * @var string $entity_class
     */
    private $entity_class;

    /**
     * @var string $form_class
     */
    private $form_class;

    /**
     * @var integer $collection_length
     */
    private $collection_length;

    /**
     * @var string $render_template
     */
    private $render_template;

    /**
     * @var array $content
     */
    private $content;


    /**
     * Set handler
     *
     * @param string $handler
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
    }

    /**
     * Get handler
     *
     * @return string 
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * Set yml_params
     *
     * @param text $ymlParams
     */
    public function setYmlParams($ymlParams)
    {
        $this->yml_params = $ymlParams;
    }

    /**
     * Get yml_params
     *
     * @return text 
     */
    public function getYmlParams()
    {
        return $this->yml_params;
    }

    /**
     * Set yml_editor_roles
     *
     * @param text $ymlEditorRoles
     */
    public function setYmlEditorRoles($ymlEditorRoles)
    {
        $this->yml_editor_roles = $ymlEditorRoles;
    }

    /**
     * Get yml_editor_roles
     *
     * @return text 
     */
    public function getYmlEditorRoles()
    {
        return $this->yml_editor_roles;
    }

    /**
     * Set yml_admin_roles
     *
     * @param text $ymlAdminRoles
     */
    public function setYmlAdminRoles($ymlAdminRoles)
    {
        $this->yml_admin_roles = $ymlAdminRoles;
    }

    /**
     * Get yml_admin_roles
     *
     * @return text 
     */
    public function getYmlAdminRoles()
    {
        return $this->yml_admin_roles;
    }

    /**
     * Set entity_class
     *
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entity_class = $entityClass;
    }

    /**
     * Get entity_class
     *
     * @return string 
     */
    public function getEntityClass()
    {
        return $this->entity_class;
    }

    /**
     * Set form_class
     *
     * @param string $formClass
     */
    public function setFormClass($formClass)
    {
        $this->form_class = $formClass;
    }

    /**
     * Get form_class
     *
     * @return string 
     */
    public function getFormClass()
    {
        return $this->form_class;
    }

    /**
     * Set collection_length
     *
     * @param integer $collectionLength
     */
    public function setCollectionLength($collectionLength)
    {
        $this->collection_length = $collectionLength;
    }

    /**
     * Get collection_length
     *
     * @return integer 
     */
    public function getCollectionLength()
    {
        return $this->collection_length;
    }

    /**
     * Set render_template
     *
     * @param string $renderTemplate
     */
    public function setRenderTemplate($renderTemplate)
    {
        $this->render_template = $renderTemplate;
    }

    /**
     * Get render_template
     *
     * @return string 
     */
    public function getRenderTemplate()
    {
        return $this->render_template;
    }

    /**
     * Set content
     *
     * @param array $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Get content
     *
     * @return array 
     */
    public function getContent()
    {
        return $this->content;
    }
}