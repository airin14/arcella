<?php

/*
 * This file is part of the Arcella package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Arcella\UserBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ArcellaUserExtension
 */
class ArcellaUserExtension extends Extension
{
    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $container->setParameter('arcella.user.salt.length', $processedConfig['salt']['length']);
        $container->setParameter('arcella.user.salt.keyspace', $processedConfig['salt']['keyspace']);
        $container->setParameter('arcella.user.token.length', $processedConfig['token']['length']);
        $container->setParameter('arcella.user.token.keyspace', $processedConfig['token']['keyspace']);
        $container->setParameter('arcella.user.token.lifetime', $processedConfig['token']['lifetime']);
    }
}
