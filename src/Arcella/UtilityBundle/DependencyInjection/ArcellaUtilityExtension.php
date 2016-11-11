<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UtilityBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Adds the configuration parameters for the utility bundle into the container.
 */
class ArcellaUtilityExtension extends Extension
{
    /**
     * Load the configuration of the utility bundle and add it to the container.
     *
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $container->setParameter('arcella.utility.token.length', $processedConfig['token']['length']);
        $container->setParameter('arcella.utility.token.keyspace', $processedConfig['token']['keyspace']);
        $container->setParameter('arcella.utility.token.lifetime', $processedConfig['token']['lifetime']);
    }
}
