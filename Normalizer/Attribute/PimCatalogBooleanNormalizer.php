<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Attribute;

use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class PimCatalogBoolean
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogBooleanNormalizer implements NormalizerInterface
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
        $normalizedAttribute = [
          'code'       => null,
          'type'       => null,
          'required'   => null,
          'labels'     => null,
          'parameters' => null,
        ];

        $availableLocales          = [];
        $attributeAvailableLocales = $attribute->getAvailableLocales();
        if (!is_null($attributeAvailableLocales)) {
            foreach ($attribute->getAvailableLocales() as $availableLocale) {
                $availableLocales [] = $availableLocale;
            }
        }

        $normalizedAttribute['required']   = $attribute->isRequired();
        $normalizedAttribute['type']       = $attribute->getAttributeType();
        $normalizedAttribute['code']       = $attribute->getCode();
        $normalizedAttribute['parameters'] = [
          'scope'             => $attribute->isScopable(),
          'localizable'       => $attribute->isLocalizable(),
          'available_locales' => $availableLocales,
          'default_value'     => null,
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
     * @param  mixed $data
     * @param  null  $format
     * @return bool
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Attribute;
    }
}
