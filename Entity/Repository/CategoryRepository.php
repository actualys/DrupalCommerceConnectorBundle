<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Entity\Repository;

use Pim\Bundle\CatalogBundle\Entity\Repository\CategoryRepository as BaseCategoryRepository;

class CategoryRepository extends BaseCategoryRepository
{
    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findRootCategories()
    {
        return $this
          ->createQueryBuilder('c')
          ->select('c')
          ->orderBy('c.left', 'ASC')
          ->where('c.level = 0');
    }
}
