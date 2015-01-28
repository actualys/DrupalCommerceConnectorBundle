<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Attribute;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\CatalogBundle\Entity\Attribute;

/**
 * Class PimCatalogIdentifier
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogIdentifierNormalizer implements NormalizerInterface
{
    /**
     * @param  object                                                $attribute
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize($attribute, $format = null, array $context = [])
    {
        /**@var Attribute $attribute * */
        $normalizedAttribute               = [
          'code'       => null,
          'type'       => null,
          'required'   => null,
          'labels'     => null,
          'parameters' => null,
        ];
        $normalizedAttribute['required']   = $attribute->isRequired();
        $normalizedAttribute['type']       = $attribute->getAttributeType();
        $normalizedAttribute['code']       = $attribute->getCode();
        $normalizedAttribute['parameters'] = [
          'scope'           => $attribute->isScopable(),
          'unique_value'    => $attribute->isUnique(),
          'max_characters'  => $attribute->getMaxCharacters(),
          'validation_rule' => $attribute->getValidationRule(),
        ];

        if ($attribute->isLocalizable()) {
            foreach ($attribute->getTranslations() as $trans) {
                $normalizedAttribute['labels'][$trans->getLocale(
                )] = $trans->getLabel();
            }
        } else {
            $normalizedAttribute['labels'][LANGUAGE_NONE] = $attribute->getLabel(
            );
        }

        return $normalizedAttribute;
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
        return $data instanceof Attribute;
    }
}
