<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;
use Akeneo\Bundle\BatchBundle\Item\AbstractConfigurableStepElement;
use Akeneo\Bundle\BatchBundle\Step\StepExecutionAwareInterface;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;

use Akeneo\Bundle\BatchBundle\Job\ExitStatus;
/**
 * Class ProductWriter
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Writer
 */
class PublishedProductWriter extends AbstractConfigurableStepElement implements ItemWriterInterface, StepExecutionAwareInterface
{

    protected $stepExecution;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     *
     * @return array
     */
    public function getConfigurationFields()
    {
        return  array();
    }

    /**
     * @param array $items
     */
    public function write(array $items)
    {
        foreach ($items as $item) {
          if (is_array($item)) {
            foreach ($item as $itm) {
              $this->stepExecution->incrementSummaryInfo('write');
              $this->stepExecution->incrementWriteCount();
            }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
      $this->stepExecution = $stepExecution;
    }


}
