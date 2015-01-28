<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Entity\Family;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\FamilyNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class FamilyProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var FamilyNormalizer
     */
    protected $familyNormalizer;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @param FamilyNormalizer $familyNormalizer The entity manager
     */
    public function __construct(FamilyNormalizer $familyNormalizer)
    {
        $this->familyNormalizer = $familyNormalizer;
    }

    /**
     * @param  mixed                $family
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($family)
    {
        return $this->normalizeFamily($family, $this->globalContext);
    }

    /**
     * @param  Family                                                $family
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws InvalidItemException
     */
    protected function normalizeFamily(Family $family, array $context)
    {
        try {
            $processedItem = $this->familyNormalizer->normalize(
              $family,
              null,
              $this->getConfiguration()
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException($e->getMessage(), [$family]);
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
