<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM;

use Doctrine\ORM\EntityManager;
use Pim\Bundle\BaseConnectorBundle\Reader\Doctrine\ORMProductReader;
use Pim\Bundle\CatalogBundle\Manager\CompletenessManager;
use Pim\Bundle\CatalogBundle\Repository\ProductRepositoryInterface;
use Pim\Bundle\CatalogBundle\Manager\ChannelManager;
use Pim\Bundle\TransformBundle\Converter\MetricConverter;
use PimEnterprise\Bundle\WorkflowBundle\Manager\PublishedProductManager;

/**
 * Class CategoryReader
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM
 */
class PublishedProductReader extends ORMProductReader {

  /** @var EntityManager */
  protected $entityManager;

  /** @var  PublishedProductManager $publishedProductManager */
  protected $publishedProductManager;

  /**
   * @var integer
   */
  protected $limit = 10;

  /**
   * @param EntityManager $em
   * @param string $className
   */
  public function __construct(
    ProductRepositoryInterface $repository,
    ChannelManager $channelManager,
    CompletenessManager $completenessManager,
    MetricConverter $metricConverter,
    EntityManager $productManager,
    EntityManager $entityManager,
    $publishedProductManager,
    $missingCompleteness = TRUE

  ) {
    parent::__construct($repository, $channelManager, $completenessManager, $metricConverter, $productManager, $missingCompleteness);
    $this->entityManager = $entityManager;
    $this->publishedProductManager = $publishedProductManager;
    $this->initialize();
  }

  /**
   * {@inheritdoc}
   */
  public function initialize() {
    $this->query = NULL;
    $this->ids = NULL;
    $this->offset = 0;
    $this->products = new \ArrayIterator();
    $this->results = new \ArrayIterator();
  }


  /**
   * {@inheritdoc}
   */
  public function read() {

    $productIds = NULL;

    if (!$this->products->valid()) {
      $this->products = $this->getNextProducts();
    }

    while (null !== $this->products && $this->products->valid()) {
      $product = $this->products->current();
      $productIds[] = $product['id'];
      $this->products->next();
      $this->stepExecution->incrementSummaryInfo('read');
    }

    return $productIds;
  }

  protected function getIds() {
    $this->entityManager->clear();
    $qb = $this->entityManager->createQueryBuilder()
      ->select('p.id')
      ->from('PimEnterprise\Bundle\WorkflowBundle\Model\PublishedProduct', 'p');

    $ids = $qb->getQuery()->execute();

    return $ids;

  }

  protected function getNextProducts() {
    $ids = NULL;

    if (NULL === $this->ids) {
      $this->ids = $this->getIds();
    }

    $currentIds = array_slice($this->ids, $this->offset, $this->limit);
    if (!empty($currentIds)) {
      $ids = new \ArrayIterator($currentIds);
      $this->offset += $this->limit;
    }

    return $ids;
  }

  /**
   * @return array
   */
  public function getResults() {
    return $this->results;
  }

  /**
   * @param array $results
   */
  public function setResults($results) {
    $this->results = $results;
  }

  /**
   * {@inheritdoc}
   */
  public function getConfigurationFields()
  {
    return [
      'channel' => [
        'type'    => 'choice',
        'options' => [
          'choices'  => $this->channelManager->getChannelChoices(),
          'required' => true,
          'select2'  => true,
          'label'    => 'pim_base_connector.export.channel.label',
          'help'     => 'pim_base_connector.export.channel.help'
        ]
      ]
    ];
  }

}
