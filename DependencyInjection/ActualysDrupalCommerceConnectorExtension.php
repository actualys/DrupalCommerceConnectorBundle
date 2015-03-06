<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ActualysDrupalCommerceConnectorExtension extends Extension
{

    /**
     * @param array            $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader(
          $container,
          new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yml');
        $loader->load('normalizers.yml');

        $loader->load('readers.yml');
        $loader->load('processors.yml');
        $loader->load('writers.yml');
        $loader->load('managers.yml');
     //   $loader->load('validation.yml');

        if (!$container->hasDefinition(
          'actualys_drupal_commerce_connector.normalizers'
        )
        ) {
            $taggedServiceHolder = new Definition();
            $taggedServiceHolder->setClass(
              'Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler\AggregatedTaggedNormalizer'
            );
            $container->setDefinition(
              'actualys_drupal_commerce_connector.normalizers',
              $taggedServiceHolder
            );
        }
    }
}
