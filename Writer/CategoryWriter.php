<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;

class CategoryWriter extends DrupalItemStep implements ItemWriterInterface
{

    protected $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function write(array $items)
    {
        //file_put_contents('categories.json', json_encode($items));
        foreach ($items[0] as $item) {
            try {
                //    $test= json_encode($item);
                $drupalResponse = $this->webservice->sendCategory($item);
            } catch (\Exception $e) {
                $event = new InvalidItemEvent(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => $item['code']]
                );
                // Logging file
                $this->eventDispatcher->dispatch(
                  EventInterface::INVALID_ITEM,
                  $event
                );
                // Loggin Interface
                $this->stepExecution->addWarning(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => $item['code']]
                );

                // Handle next element.
            }
            $this->stepExecution->incrementSummaryInfo('write');
        }
    }
}
