<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Interface NormalizerInterface
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
interface ProductValueNormalizerInterface
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
    );
}
