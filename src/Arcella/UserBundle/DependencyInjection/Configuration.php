<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('arcella_user');

        $rootNode
            ->children()
                ->arrayNode('salt')
                    ->children()
                        ->scalarNode('length')->end()
                        ->scalarNode('keyspace')->end()
                    ->end()
                ->end()
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
