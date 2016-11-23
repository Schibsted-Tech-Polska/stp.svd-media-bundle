<?php

namespace Svd\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
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
        $loader->load('repositories.yml');
        $loader->load('services.yml');

        $container->setParameter('svd_media.adapter', $config['adapter']);
        $container->setParameter('svd_media.base_url', $config['base_url']);
        $container->setParameter('svd_media.file_novelty_period', $config['file_novelty_period']);
        $container->setParameter('svd_media.liip_imagine.filter_mapper', $config['liip_imagine']['filter_mapper']);
    }

    /**
     * Prepend
     *
     * @param ContainerBuilder $container container
     */
    public function prepend(ContainerBuilder $container)
    {
        $twigConfig = $container->getExtensionConfig('twig')[0];

        if (!isset($twigConfig['form_themes'])) {
            $twigConfig['form_themes'] = [];
        }
        $twigConfig['form_themes'][] = 'SvdMediaBundle:Macros:media_field.html.twig';
        $container->prependExtensionConfig('twig', $twigConfig);
    }
}
