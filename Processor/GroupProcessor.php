<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Entity\Group;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\GroupNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Item\DrupalItemStep;

class GroupProcessor extends DrupalItemStep implements ItemProcessorInterface
{
    /**
     * @var GroupNormalizer
     */
    protected $groupNormalizer;

    /**
     * @var array
     */
    protected $globalContext = array();

    /**
     * @param GroupNormalizer $groupNormalizer
     */
    public function __construct(GroupNormalizer $groupNormalizer)
    {
        $this->groupNormalizer = $groupNormalizer;
    }

    /**
     * @param  mixed                $group
     * @return array|mixed
     * @throws InvalidItemException
     */
    public function process($group)
    {
        return $this->normalizeGroup($group, $this->globalContext);
    }

    /**
     * @param  Group                                                 $group
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws InvalidItemException
     */
    protected function normalizeGroup(Group $group, array $context)
    {
        try {
            $processedItem = $this->groupNormalizer->normalize(
              $group,
              $context
            );
        } catch (NormalizeException $e) {
            throw new InvalidItemException($e->getMessage(), [$group]);
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
