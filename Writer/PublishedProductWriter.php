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
use Actualys\Bundle\DrupalCommerceConnectorBundle\Cleaner\PublishedProductMediaCleaner;


/**
 * Class ProductWriter
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Writer
 */
class PublishedProductWriter extends DrupalItemStep implements ItemWriterInterface, StepExecutionAwareInterface
{



  /** @var  PublishedProductMediaCleaner $publishedProductMediaCleaner */
  protected $publishedProductMediaCleaner;

    protected $stepExecution;

    public function __construct(EventDispatcher $eventDispatcher, PublishedProductMediaCleaner $publishedProductMediaCleaner)
    {
      $this->eventDispatcher = $eventDispatcher;
      $this->publishedProductMediaCleaner    = $publishedProductMediaCleaner;
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

      $rc = $this->stepExecution->getReadCount();
      $wc= $this->stepExecution->getWriteCount();

      if ($rc > 0 && $rc == $wc) {
        $this->publishedProductMediaCleaner->clean();
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
