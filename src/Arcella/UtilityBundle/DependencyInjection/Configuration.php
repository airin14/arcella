<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UtilityBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Defines the available configuration parameters for the utility bundle.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * Get the configuration parameters as TreeBuilder object.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('arcella_utility');

        $rootNode
            ->children()
                ->arrayNode('token')
                    ->children()
                        ->scalarNode('lifetime')->end()
                        ->scalarNode('length')->end()
                        ->scalarNode('keyspace')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
