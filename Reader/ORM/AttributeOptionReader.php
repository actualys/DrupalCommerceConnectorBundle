<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM;

use Pim\Bundle\BaseConnectorBundle\Reader\ORM\EntityReader;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Entity\Repository\AttributeRepository;
use Doctrine\ORM\EntityManager;

/**
 * Class AttributeOptionReader
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM
 */
class AttributeOptionReader extends EntityReader
{
    /**
     * @var AttributeRepository $repository
     */
    protected $repository;

    /**
     * @param EntityManager       $em
     * @param string              $className
     * @param AttributeRepository $repository
     */
    public function __construct(
      EntityManager $em,
      $className,
      AttributeRepository $repository
    ) {
        parent::__construct($em, $className);

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getQuery()
    {
        if (!$this->query) {
            $this->query = $this->getRepository()
              ->findMultiValuedOptions()
              ->getQuery();
        }

        return $this->query;
    }

    /**
     * Get the custom category repository
     * @return AttributeRepository
     */
    protected function getRepository()
    {
        return $this->repository;
    }
}
