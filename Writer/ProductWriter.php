<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Writer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;
use Akeneo\Bundle\BatchBundle\Item\ItemWriterInterface;
use Akeneo\Bundle\BatchBundle\Event\InvalidItemEvent;
use Guzzle\Http\Exception\ClientErrorResponseException;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Akeneo\Bundle\BatchBundle\Event\EventInterface;


use Akeneo\Bundle\BatchBundle\Job\ExitStatus;
/**
 * Class ProductWriter
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Writer
 */
class ProductWriter extends DrupalItemStep implements ItemWriterInterface
{
    public function __construct(EventDispatcher $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->productandler   = $eventDispatcher;
    }

    /**
     *
     * @return array
     */
    public function getConfigurationFields()
    {
        return parent::getConfigurationFields();
    }

    /**
     * @param array $items
     */
    public function write(array $items)
    {
        foreach ($items as $item) {
            try {
          //file_put_contents('product.json', json_encode($item));
              $this->webservice->sendProduct($item);
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
            $this->stepExecution->incrementWriteCount();

        }
    }


}
