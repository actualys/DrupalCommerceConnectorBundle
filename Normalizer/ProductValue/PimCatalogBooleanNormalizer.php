<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogBoolean
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogBooleanNormalizer implements ProductValueNormalizerInterface
{
    /**
     * @param array        $drupalProduct
     * @param ProductValue $productValue
     * @param string       $field
     * @param array        $context
     */
    public function normalize(
      array &$drupalProduct,
      $productValue,
      $field,
      array $context = array()
    ) {
        $drupalProduct['values'][$field][$context['locale']][] = array(
          'type' => 'pim_catalog_boolean',
          'value' => $productValue->getBoolean(),
        );
    }
}
