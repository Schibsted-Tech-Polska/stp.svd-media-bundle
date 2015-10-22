<?php

namespace Svd\MediaBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Dependency injection
 */
class SvdMediaExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Load config and services
     *
     * @param array            $configs   configs
     * @param ContainerBuilder $container container builder
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('parameters.yml');

        $loader->load('repositories.yml');
        $loader->load('services.yml');

        $def = $container->getDefinition('svd_media.manager.media');

        $def->addMethodCall('setAdapter', [
            'map' => new Reference('knp_gaufrette.filesystem_map'),
            'adapter' => $config['adapter'],
        ]);

        $container->setDefinition('svd_media.manager.media', $def);

//        die;
    }

    public function prepend(ContainerBuilder $container)
    {
    }
}
