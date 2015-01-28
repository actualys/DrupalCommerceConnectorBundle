<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogMultiSelect
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogMultiSelectNormalizer extends PimCatalogSimpleSelectNormalizer
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
        $options = $productValue->getOptions();
        /** @var \Pim\Bundle\CatalogBundle\Entity\Option $subValue */
        foreach ($options->getValues() as $subValue) {
            $drupalProduct['values'][$field][$context['locale']][] = [
              'type' => 'pim_catalog_simpleselect',
              'code' => $subValue->getCode(),
            ];
        }
    }
}
