<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class AggregatedTaggedServicesPass
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler
 */
class AggregatedTaggedServicesPass implements CompilerPassInterface
{
    /**
     * @see Symfony\Component\DependencyInjection\Compiler.CompilerPassInterface::process()
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(
          'actualys_drupal_commerce_connector.normalizers'
        )
        ) {
            return;
        }

        $definition = $container->getDefinition(
          'actualys_drupal_commerce_connector.normalizers'
        );
        $taggedServices = $container->findTaggedServiceIds(
          'actualys_drupal_commerce_connector.normalizer.attribute'
        );
        $taggedServices += $container->findTaggedServiceIds(
          'actualys_drupal_commerce_connector.normalizer.product_value'
        );
        // Register all normalizers.
        foreach ($taggedServices as $id => $tagAttributes) {
            foreach ($tagAttributes as $attributes) {
                $definition->addMethodCall(
                  'addNormalizer',
                  array(new Reference($id), $attributes['alias'])
                );
            }
        }
    }
}
