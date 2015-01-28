<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogNumber
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogNumberNormalizer implements ProductValueNormalizerInterface
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
        $decimal = $productValue->getDecimal();
        if (null !== $decimal) {
            $drupalProduct['values'][$field][$context['locale']][] = array(
              'type' => 'pim_catalog_number',
              'value' => (double) $decimal,
            );
        }
    }
}
