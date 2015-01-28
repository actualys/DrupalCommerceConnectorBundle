<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductPrice;
use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogPriceCollection
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogPriceCollectionNormalizer implements ProductValueNormalizerInterface
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
        $prices = $productValue->getPrices();
        /** @var ProductPrice $price */
        foreach ($prices as $price) {
            $drupalProduct['values'][$field][$context['locale']][] = [
              'type'     => 'pim_catalog_price_collection',
              'amount'   => (double) $price->getData(),
              'currency' => $price->getCurrency(),
            ];
        }
    }
}
