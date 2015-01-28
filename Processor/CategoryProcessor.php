<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Entity\Category;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\CategoryNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class CategoryProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var CategoryNormalizer
     */
    protected $categoryNormalizer;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @param CategoryNormalizer $categoryNormalizer The entity manager
     */
    public function __construct(CategoryNormalizer $categoryNormalizer)
    {
        $this->categoryNormalizer = $categoryNormalizer;
    }

    /**
     * @param  mixed                $categories
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($categories)
    {
        $result = [];
        foreach ($categories as $category) {
            $result[$category->getCode()] = $this->normalizeCategory(
              $category,
              $this->globalContext
            );
        }

        return $result;
    }

    /**
     * @param  Category                                              $category
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws InvalidItemException
     */
    protected function normalizeCategory(Category $category, array $context)
    {
        try {
            $processedItem = $this->categoryNormalizer->normalize(
              $category,
              $context
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException($e->getMessage(), [$category]);
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
