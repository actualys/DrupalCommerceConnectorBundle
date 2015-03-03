<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Model\Product;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;
use Pim\Bundle\CatalogBundle\Manager\AssociationManager;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\AssociationNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class AssociationProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var AssociationNormalizer $associationNormalizer
     */
    protected $associationNormalizer;

    /** @var AssociationManager $associationManager */
    protected $associationManager;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @var ProductManager $productManager
     */
    protected $productManager;

    /**
     * @param AssociationNormalizer $associationNormalizer
     * @param ProductManager        $productManager
     * @param AssociationManager    $associationManager
     */
    public function __construct(
      AssociationNormalizer $associationNormalizer,
      ProductManager $productManager,
      AssociationManager $associationManager
    ) {
        $this->associationNormalizer = $associationNormalizer;
        $this->productManager        = $productManager;
        $this->associationManager          = $associationManager;
    }

    /**
     * @param  mixed                $product
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($product)
    {
        $result = [];

        return $this->normalizeAssociation(
          $product,
          $this->globalContext
        );;
    }

    /**
     * @param  Product                                               $product
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws InvalidItemException
     */
    protected function normalizeAssociation(Product $product, array $context)
    {
        try {
            $processedItem = $this->associationNormalizer->normalize(
              $product,
              $context
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException($e->getMessage(), [$product]);
        }

        return $processedItem;
    }

    public function getConfigurationFields()
    {
        return parent::getConfigurationFields();
    }

    /**
     * @param StepExecution $stepExecution
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }
}
