<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Akeneo\Bundle\BatchBundle\Entity\JobInstance;
use Pim\Bundle\CatalogBundle\Manager\ChannelManager;
use Pim\Bundle\DeltaExportBundle\Manager\ProductExportManager;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Symfony\Component\EventDispatcher\EventDispatcher;

class DeltaProductWriter extends ProductWriter
{
    /**
     * @var ProductExportManager
     */
    protected $productExportManager;

    /**
     * @var JobInstance
     */
    protected $jobInstance;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Constructor
     *
     * @param EventDispatcher      $eventDispatcher
     * @param ChannelManager       $channelManager
     * @param ProductExportManager $productExportManager
     */
    public function __construct(
      EventDispatcher $eventDispatcher,
      ChannelManager $channelManager,
      ProductExportManager $productExportManager
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->productExportManager = $productExportManager;
    }

    /**
     * @param array $items
     */
    public function write(array $items)
    {
        parent::write($items);

        foreach ($items as $item) {
            $this->productExportManager->updateProductExport(
              $item['products']['sku'],
              $this->jobInstance
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        parent::setStepExecution($stepExecution);

        $this->jobInstance = $stepExecution->getJobExecution()->getJobInstance(
        );
    }
}
