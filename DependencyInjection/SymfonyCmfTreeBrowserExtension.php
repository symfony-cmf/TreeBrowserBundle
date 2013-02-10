<?php

namespace Symfony\Cmf\Bundle\TreeBrowserBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\HttpKernel\DependencyInjection\Extension,
    Symfony\Component\DependencyInjection\Reference,
    Symfony\Component\DependencyInjection\ContainerInterface,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

/**
  * @author Lukas Kahwe Smith <smith@pooteeweet.org>
  */
class SymfonyCmfTreeBrowserExtension extends Extension
{
    /**
     * Loads the services based on your application configuration.
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));

        $tree = $container->getDefinition('symfony_cmf_tree_browser.phpcr_tree');
        $tree->replaceArgument(1, $config['session_name']);

        $loader->load('services_browser.xml');

        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['DoctrinePHPCRBundle'])) {
            $loader->load('phpcr.xml');
            $phpcr_tree = $container->getDefinition('symfony_cmf_tree_browser.phpcr_tree');
            $phpcr_tree->replaceArgument(1, $config['session_name']);
        }

        $loader->load('tree.xml');
    }
}
