<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;

class AssociationWriter extends DrupalItemStep implements ItemWriterInterface
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
        //file_put_contents('product_association.json', json_encode($items));
        foreach ($items as $item) {
            try {
                // Send only when association exist
                if (count($item[key($item)]) > 0) {
                    $this->webservice->sendAssociation($item);
                }
            } catch (\Exception $e) {
                $event = new InvalidItemEvent(
                  __CLASS__,
                  $e->getMessage(),
                  array(),
                  ['sku' => array_key_exists('sku', $item) ? $item['sku'] : 'NULL']
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
                  ['sku' => array_key_exists('sku', $item) ? $item['sku'] : 'NULL']
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
