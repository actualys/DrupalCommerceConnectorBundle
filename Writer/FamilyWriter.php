<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;
use Guzzle\Http\Exception\ClientErrorResponseException;


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
        foreach ($items as $item) {
            try {
                $this->webservice->sendFamily($item);
            } catch (\Exception $e) {
                $event = new InvalidItemEvent(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['code' => array_key_exists('code', $item) ? $item['code'] : 'NULL']
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
                  ['code' => array_key_exists('code', $item) ? $item['code'] : 'NULL']
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
            $this->stepExecution->incrementSummaryInfo('write');
        }
    }
}
