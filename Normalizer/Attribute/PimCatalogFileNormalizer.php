<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Attribute;

use Pim\Bundle\CatalogBundle\Entity\Attribute;

/**
 * Class PimCatalogFile
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogFileNormalizer extends AbstractMediaNormalizer
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
