<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogText
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogTextNormalizer implements ProductValueNormalizerInterface
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
        $text = $productValue->getText();
        if (is_null($text)) {
            $text = $productValue->getVarchar();
        }
        if (null !== $text) {
            $drupalProduct['values'][$field][$context['locale']][] = array(
              'type' => 'pim_catalog_text',
              'value' => $text,
            );
        }
    }
}
