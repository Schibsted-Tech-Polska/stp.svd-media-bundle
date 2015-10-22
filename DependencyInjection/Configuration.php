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
                ->append($this->addTransformersNode())
            ->end();

        return $treeBuilder;
    }

    /**
     * Add Transformers node
     *
     * @return ArrayNodeDefinition
     */
    public function addTransformersNode()
    {
        $builder = new TreeBuilder();
        $node = $builder->root('transformers');

        $node
            ->isRequired()
            ->requiresAtLeastOneElement()
            ->useAttributeAsKey('name')
            ->prototype('array')
                ->children()
                    ->floatNode('ratio')
                        ->defaultValue(null)
                        ->beforeNormalization()
                        ->ifString()
                        ->then(function ($value) {
                            $ratio = explode('/', $value);
                            if (preg_match('#^\d+\/\d+$#', $value) && isset($ratio[0]) && isset($ratio[1])) {
                                $value = $ratio[0] / $ratio[1];
                            }
                            return $value;
                        })
                        ->end()
                    ->end()
                    ->integerNode('size')
                        ->defaultValue(null)
                    ->end()
                    ->booleanNode('is_default')
                        ->defaultFalse()
                    ->end()
                    ->scalarNode('folder')
                        ->defaultValue(null)
                    ->end()
                ->end()
            ->end();

        return $node;
    }
}
