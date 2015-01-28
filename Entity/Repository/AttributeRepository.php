<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Entity\Repository;

use Pim\Bundle\CatalogBundle\Entity\Repository\AttributeRepository as BaseAttributeRepository;

class AttributeRepository extends BaseAttributeRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findMultiValuedOptions()
    {
        return $this
          ->createQueryBuilder('c')
          ->select('c')
          ->where('c.attributeType IN(:param1, :param2)')
          ->setParameters(
            array(
              'param1' => 'pim_catalog_multiselect',
              'param2' => 'pim_catalog_simpleselect',
            )
          );
    }
}
