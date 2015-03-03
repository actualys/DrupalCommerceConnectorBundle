<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;

class FamilyWriter extends DrupalItemStep implements ItemWriterInterface
{

    protected $eventDispatcher;

    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param array $items
     */
    public function write(array $items)
    {

        //file_put_contents('families.json', json_encode($items));
        $i = 0;
        foreach ($items as $item) {
            try {
                $drupalResponse = $this->webservice->sendFamily($item);
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
            $i++;
        }
    }
}
