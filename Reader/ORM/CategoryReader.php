<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM;

use Pim\Bundle\BaseConnectorBundle\Reader\ORM\EntityReader;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Entity\Repository\CategoryRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class CategoryReader
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM
 */
class CategoryReader extends EntityReader
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @param EntityManager      $em
     * @param string             $className
     * @param CategoryRepository $repository
     */
    public function __construct(
      EntityManager $em,
      $className,
      CategoryRepository $repository
    ) {
        parent::__construct($em, $className);

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $entities = [];

        while ($entity = parent::read()) {
            $this->stepExecution->incrementReadCount();

            $entities[] = $entity;
        }

        return empty($entities) ? null : $entities;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        if (!$this->query) {
            $this->query = $this->getRepository()
              ->findRootCategories()
              ->getQuery();
        }

        return $this->query;
    }

    /**
     * Get the custom category repository
     * @return CategoryRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }
}
