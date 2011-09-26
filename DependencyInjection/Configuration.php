<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mucho_mas_facil_inline_editable_contents');

        $rootNode
            ->children()
                ->arrayNode('widgets')
                ->useAttributeAsKey('name')
                ->prototype('array')
                    ->children()
                        ->scalarNode('yml_params')->end()
                        ->scalarNode('yml_editor_roles')->end()
                        ->scalarNode('yml_admin_roles')->end()
                        ->scalarNode('entity_class')->end()
                        ->scalarNode('form_class')->end()
                        ->scalarNode('render_template')->end()
                        ->scalarNode('form_template')->end()
                    ->end()
                ->end()
            ->end()

        ;

        $rootNode
            ->children()
                ->arrayNode('ckeditor_options')
                ->useAttributeAsKey('name')
                    ->prototype('scalar')->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
