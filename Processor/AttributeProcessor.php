<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\AttributeNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class AttributeProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var AttributeNormalizer $attributeNormalizer
     */
    protected $attributeNormalizer;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @param AttributeNormalizer $attributeNormalizer The entity manager
     */
    public function __construct(AttributeNormalizer $attributeNormalizer)
    {
        $this->attributeNormalizer = $attributeNormalizer;
    }

    /**
     * @param  mixed                $attribute
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($attribute)
    {
        return $this->normalizeAttribute($attribute, $this->globalContext);
    }

    /**
     * @param  Attribute            $attribute
     * @param  array                $context
     * @return array
     * @throws InvalidItemException
     */
    protected function normalizeAttribute(Attribute $attribute, array $context)
    {
        try {
            $processedItem = $this->attributeNormalizer->normalize(
              $attribute,
              $context
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException($e->getMessage(), [$attribute]);
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
