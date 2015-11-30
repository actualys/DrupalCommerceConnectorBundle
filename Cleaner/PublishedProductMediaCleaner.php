<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Cleaner;

use Actualys\Bundle\XmlConnectorBundle\XmlReader\ActualysSimpleXMLElement;
use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Doctrine\ORM\EntityManager;
use Actualys\Bundle\XmlConnectorBundle\Helper\Helper;
use Pim\Bundle\CatalogBundle\Entity\Repository\AttributeRepository;
use Pim\Bundle\CatalogBundle\Entity\Repository\CategoryRepository;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Pim\Bundle\BaseConnectorBundle\Reader\CachedReader;
use Pim\Bundle\CatalogBundle\Entity\Repository\AssociationTypeRepository;
use Pim\Bundle\CatalogBundle\Entity\Repository\AttributeOptionRepository;
use Pim\Bundle\CatalogBundle\Doctrine\ORM\ProductRepository;

class PublishedProductMediaCleaner
{

  protected $container;


  public function __construct($container)
  {
    $this->container = $container;
  }


  public function clean()
  {

    $rootDir = $this->container->get('kernel')->getRootDir();
    $publishedMediaDir = $rootDir . '/uploads/product';
    $files = scandir($publishedMediaDir);
    $exploded_filenames = array();

    foreach ($files as $key_file =>$file) {
      if (preg_match('/^published/', $file)) {
        preg_match('/published-(?P<pid>[[:alnum:]]+)-(?P<sku>[[:alnum:]]+)-(?P<fieldname>[[:alnum:]]+_[[:alnum:]]+)--(?P<channel>[[:alnum:]]+)-(?P<timestamp>[[:alnum:]]+)-(?P<filename>.+)/', $file, $matches);
        foreach ($matches as $key => $match ){
          $exploded_filenames[$matches['sku'].'-'.$matches['fieldname']][$matches['timestamp']]['filename'] = $matches[0];
        }
      }
    }

    foreach ($exploded_filenames as $id => $timestamps) {
      arsort($exploded_filenames[$id]);
      array_shift($exploded_filenames[$id]);
      foreach ($exploded_filenames[$id] as $timestamp => $filename) {
        $filename['filename'];
        unlink($publishedMediaDir .'/'. $filename['filename']);
      }
    }
  }
}