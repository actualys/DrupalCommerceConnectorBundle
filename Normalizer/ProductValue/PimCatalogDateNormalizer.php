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
     * @param array $drupalProduct
     * @param \Pim\Bundle\CatalogBundle\Model\ProductValue $productValue
     * @param string $field
     * @param array $context
     */
    public function normalize(
      array &$drupalProduct,
      $productValue,
      $field,
      array $context = array()
    ) {

        $drupalProduct['values'][$field][$context['locale']][] = array(
          'type' => 'pim_catalog_date',
          'value' => ($productValue->getDate() instanceof \DateTime) ? $productValue->getDate()->format('c') : null,
        );
    }
}
