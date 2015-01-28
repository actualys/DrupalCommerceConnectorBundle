<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle;

use Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler\AggregatedTaggedServicesPass;
use Akeneo\Bundle\BatchBundle\Connector\Connector;
use Symfony\Component\DependencyInjection\ContainerBuilder;

define('LANGUAGE_NONE', 'und');

class ActualysDrupalCommerceConnectorBundle extends Connector
{
    /**
     * @see Symfony\Component\HttpKernel\Bundle.Bundle::registerExtensions()
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AggregatedTaggedServicesPass());
    }
}
