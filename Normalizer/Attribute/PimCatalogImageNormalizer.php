<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Attribute;

use Pim\Bundle\CatalogBundle\Entity\Attribute;

/**
 * Class PimCatalogImage
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogImageNormalizer extends AbstractMediaNormalizer
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

        $allowed_extensions = [];
        foreach ($attribute->getAllowedExtensions() as $allowed_extension) {
            $allowed_extensions [] = $allowed_extension;
        }

        $normalizedAttribute['required']   = $attribute->isRequired();
        $normalizedAttribute['type']       = $attribute->getAttributeType();
        $normalizedAttribute['code']       = $attribute->getCode();
        $normalizedAttribute['parameters'] = [
          'scope'              => $attribute->isScopable(),
          'localizable'        => $attribute->isLocalizable(),
          'available_locales'  => $availableLocales,
          'max_file_size'      => $attribute->getMaxFileSize(),
          'allowed_extensions' => $allowed_extensions,
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
}
