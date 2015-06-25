<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler;

use Akeneo\Bundle\BatchBundle\DependencyInjection\Compiler\RegisterJobsPass;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Registers PIM EE batch jobs if the EE is installed
 *
 * @author Antoine Guigan <antoine@akeneo.com>
 */
class PimEEBatchJobs extends RegisterJobsPass
{
    public function process(ContainerBuilder $container)
    {
        if (in_array(
            'PimEnterprise\Bundle\CatalogBundle\PimEnterpriseCatalogBundle',
            $container->getParameter('kernel.bundles'))
        ) {
            $registry = $container->getDefinition('akeneo_batch.connectors');
            $configFile = realpath(__DIR__ . '/../../Resources/config/pimee_batch_jobs.yml');
            $container->addResource(new FileResource($configFile));

            $this->registerJobs($registry, $configFile);
        }
    }
}