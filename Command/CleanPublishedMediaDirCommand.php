<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\DialogHelper;
use Doctrine\ORM\EntityManager;
use Pim\Bundle\ImportExportBundle\Entity\Repository\JobInstanceRepository;
use Akeneo\Bundle\BatchBundle\Entity\JobInstance;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Cleaner\PublishedProductMediaCleaner;


class CleanPublishedMediaDirCommand extends ContainerAwareCommand {

  protected $publishedProductMediaCleaner;

  public function __construct($name = NULL) {
    parent::__construct($name);
  }

  protected function configure() {
    $this
      ->setName('actualys:clean-published-media-dir')
      ->setDescription('remove duplicated image files prefixed by published-* for already published product');
  }


  /*
   *
   */
  protected function execute(InputInterface $input, OutputInterface $output) {

    $this->publishedProductMediaCleaner =  $this->getContainer()->get('actualys_drupal_commerce_connector.cleaner.published_product_media');
    $this->publishedProductMediaCleaner->clean();

  }
}

