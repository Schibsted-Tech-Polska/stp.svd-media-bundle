<?php

namespace Svd\MediaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;

/**
 * Dependency injection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get config tree builder
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('svd_media');

        $rootNode
            ->children()
                ->scalarNode('adapter')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('base_url')
                    ->isRequired()
                    ->cannotBeEmpty()
                ->end()
                ->scalarNode('file_novelty_period')
                    ->defaultValue(24 * 60 * 60)
                ->end()
                ->arrayNode('liip_imagine')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->variableNode('filter_mapper')
                            ->defaultValue([
                                'admin_thumbnail' => 'thumbnail',
                            ])
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
