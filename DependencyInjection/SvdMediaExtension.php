<?php

namespace Svd\MediaBundle\DependencyInjection;

use Svd\MediaBundle\Transformer\ImageTransformer;
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
        $loader->load('parameters.yml');

        $loader->load('repositories.yml');
        $loader->load('services.yml');

        $def = $container->getDefinition('svd_media.manager.media');

        $def->addMethodCall('setAdapter', [
            'map' => new Reference('knp_gaufrette.filesystem_map'),
            'adapter' => $config['adapter'],
        ]);

        $defaultTransformer = null;
        $transformers = [];
        foreach ($config['transformers'] as $key => $options) {
            if (!$options['folder']) {
                $options['folder'] = $key;
            }
            $transformer = $this->getTransformer($options);

            $transformers[] = $transformer;
            if ($options['is_default'] === true) {
                $defaultTransformer = $key;
            }
        }

        if (!$defaultTransformer) {
            throw new InvalidConfigurationException('Default transformer must be set!');
        }

        $def->addMethodCall('setTransformers', [
            'transformers' => $transformers,
        ]);
        $def->addArgument('defaultTransformer', $defaultTransformer);

        $container->setDefinition('svd_media.manager.media', $def);
    }

    public function prepend(ContainerBuilder $container)
    {
    }

    /**
     * Get transformer
     *
     * @param array $options options
     *
     * @return Definition
     */
    protected function getTransformer(array $options)
    {
        $def = new Definition('Svd\MediaBundle\Transformer\ImageTransformer', [null, null, null, null]);
        $def->replaceArgument(0, $options['folder']);
        $def->replaceArgument(1, $options['is_default']);
        $def->replaceArgument(2, $options['ratio']);
        $def->replaceArgument(3, $options['size']);

        return $def;
    }
}
