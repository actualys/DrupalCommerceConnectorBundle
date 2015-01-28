<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Entity\Attribute;

/**
 * Class PimCatalogDate
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogDateNormalizer implements ProductValueNormalizerInterface
{
    /**
     * @param  object                                                $attribute
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize(
      array &$drupalProduct,
      $productValue,
      $field,
      array $context = array()
    ) {
        echo "test";
        $drupalProduct['values'][$field][] = array(
          'type' => 'pim_catalog_date',
          'value' => (double) $decimal,
        );
    }
}
