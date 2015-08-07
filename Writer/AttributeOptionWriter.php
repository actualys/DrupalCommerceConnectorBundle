<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;
use Akeneo\Bundle\BatchBundle\Job\ExitStatus;

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
                $this->webservice->sendAttributeOption($item);

            } catch (\Exception $e) {
                $event = new InvalidItemEvent(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => key($item)]
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
                  ['code' => key($item) ]
                );

                /** @var ClientErrorResponseException  $e */
                if ($e->getResponse()->getStatusCode() <= 404) {
                    $e = new \Exception($e->getResponse()->getReasonPhrase());
                    $this->stepExecution->addFailureException($e);
                    $exitStatus = new ExitStatus(ExitStatus::FAILED);
                    $this->stepExecution->setExitStatus($exitStatus);
                }
                // Handle next element.
            }
            $this->stepExecution->incrementWriteCount();
            $this->stepExecution->incrementSummaryInfo('write');
        }
    }
}
