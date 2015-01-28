<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Pim\Bundle\CatalogBundle\Entity\AttributeOption;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class AttributeOptionNormalizer implements NormalizerInterface
{
    /**
     * @param  object AttributeOption                                $attributeOption
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize(
      $attributeOption,
      $format = null,
      array $context = []
    ) {
        /**@var AttributeOptionNormalizer $attributeOption * */
        $normalizedAttributeOption = [
          'labels' => [],
        ];

        /** @var AttributeOption $attributeOption */
        foreach ($attributeOption->getOptionValues() as $optionValue) {
            $normalizedAttributeOption['labels'][$optionValue->getLocale(
            )] = $optionValue->getValue();
        }

        return $normalizedAttributeOption;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer
     *
     * @param mixed  $data   Data to normalize.
     * @param string $format The format being (de-)serialized from or into.
     *
     * @return boolean
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof AttributeOption;
    }
}
