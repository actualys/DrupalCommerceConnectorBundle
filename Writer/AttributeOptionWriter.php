<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;

/**
 * Class AttributeOptionWriter
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Writer
 */
class AttributeOptionWriter extends DrupalItemStep implements ItemWriterInterface
{
    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array $items
     */
    public function write(array $items)
    {
        //      file_put_contents('options.json', json_encode($items));

        foreach ($items as $item) {
            try {
                $test = json_encode($item);
                $this->webservice->sendAttributeOption($item);
                $this->stepExecution->incrementSummaryInfo('write');
            } catch (\Exception $e) {
                $event = new InvalidItemEvent(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => key($item)]
                );
                // Logging File
                $this->eventDispatcher->dispatch(
                  EventInterface::INVALID_ITEM,
                  $event
                );
                // Logging Interface
                $this->stepExecution->addWarning(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => key($item)]
                );
            }
        }
    }
}
