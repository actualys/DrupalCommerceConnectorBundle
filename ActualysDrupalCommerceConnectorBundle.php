<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle;

use Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler\AggregatedTaggedServicesPass;
use Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler\PimEEBatchJobs;
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
        $container->addCompilerPass(new PimEEBatchJobs());
    }
}
