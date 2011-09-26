<?php

namespace MuchoMasFacil\InlineEditableContentsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class MuchoMasFacilInlineEditableContentsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $parameter_configs[] = array(
            'widgets' => $container->getParameter('mucho_mas_facil_inline_editable_contents.widgets'),
            'ckeditor_options' => $container->getParameter('mucho_mas_facil_inline_editable_contents.ckeditor_options'),
            );
        $parameter_configs[] = $config;

        $final_config = $this->processConfiguration($configuration, $parameter_configs);

        $container->setParameter('mucho_mas_facil_inline_editable_contents.widgets', $final_config['widgets']);
        $container->setParameter('mucho_mas_facil_inline_editable_contents.ckeditor_options', $final_config['ckeditor_options']);
    }
}
