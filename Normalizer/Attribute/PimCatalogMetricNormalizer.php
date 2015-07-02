<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Attribute;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\CatalogBundle\Entity\Attribute;

/**
 * Class PimCatalogMetric
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogMetricNormalizer implements NormalizerInterface
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
        $normalizedAttribute       = [
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
          'scope'               => $attribute->isScopable(),
          'localizable'         => $attribute->isLocalizable(),
          'available_locales'   => $availableLocales,
          'default_value'       => $attribute->getDefaultValue(),
          'negative_allowed'    => (bool) $attribute->isNegativeAllowed(),
          'decimale_allowed'    => (bool) $attribute->isDecimalsAllowed(),
          'number_min'          => (bool) $attribute->getNumberMin(),
          'number_max'          => (bool) $attribute->getNumberMax(),
          'metric_family'       => $attribute->getMetricFamily(),
          'default_metric_unit' => $attribute->getDefaultMetricUnit(),
        ];

      $translations = $attribute->getTranslations();
      if (!empty($translations)  && count($attribute->getTranslations()) > 0) {
        foreach ($attribute->getTranslations() as $trans) {
          $normalizedAttribute['labels'][$trans->getLocale(
          )] = $trans->getLabel();
        }
        $normalizedAttribute['labels'][LANGUAGE_NONE] = $attribute->getLabel( );

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
