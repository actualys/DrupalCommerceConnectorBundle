<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Entity\AttributeOption;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\AttributeOptionNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class AttributeOptionProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var AttributeOptionNormalizer
     */
    protected $attributeOptionNormalizer;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @param AttributeOptionNormalizer $attributeOptionNormalizer The entity manager
     */
    public function __construct(
      AttributeOptionNormalizer $attributeOptionNormalizer
    ) {
        $this->attributeOptionNormalizer = $attributeOptionNormalizer;
    }

    /**
     * @param  Attribute            $attribute
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($attribute)
    {
        $result = [
          'code'    => $attribute->getCode(),
          'labels'  => array(),
          'options' => array(),
        ];

        foreach ($attribute->getTranslations() as $trans) {
            $result['labels'][$trans->getLocale()] = $trans->getLabel();
        }
        foreach ($attribute->getOptions() as $attributeOption) {
            $result['options'][$attributeOption->getCode(
            )] = $this->normalizeOption($attributeOption, $this->globalContext);
        }

        return $result;
    }

    /**
     * @param  AttributeOption      $attributeOptionNormalizer
     * @param  array                $context
     * @return mixed
     * @throws InvalidItemException
     */
    protected function normalizeOption(
      AttributeOption $attributeOptionNormalizer,
      array $context
    ) {
        try {
            $processedItem = $this->attributeOptionNormalizer->normalize(
              $attributeOptionNormalizer,
              $context
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException(
              $e->getMessage(),
              [$attributeOptionNormalizer]
            );
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
