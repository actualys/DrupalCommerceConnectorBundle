<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Model\ProductValue;

/**
 * Class PimCatalogMetric
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
class PimCatalogMetricNormalizer implements ProductValueNormalizerInterface
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
        $metric = $productValue->getMetric();
        // Process only if the value is not null
        if (is_object($metric)) {
            if ($metric->getData() && $metric->getBaseUnit()) {
                $drupalProduct['values'][$field][$context['locale']][] = array(
                  'type' => 'pim_catalog_metric',
                  'value' => (double) $metric->getData(),
                  'unit' => $metric->getUnit(),
                );
            }
        }

    }
}
