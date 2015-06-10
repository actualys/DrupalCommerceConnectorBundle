<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\ProductValue;

use Pim\Bundle\CatalogBundle\Manager\MediaManager;

/**
 * Class AbstractMediaNormalizer
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer
 */
abstract class AbstractMediaNormalizer implements ProductValueNormalizerInterface
{
    /**
     * @var string
     */
    protected $rootDir;


    /**
     * @var MediaManager
     */
    protected $mediaManager;

    public function normalize(
      array &$drupalProduct,
      $productValue,
      $field,
      array $context = array()
    ) {
    }

    /**
     * @return MediaManager
     */
    public function getMediaManager()
    {
        return $this->mediaManager;
    }

    /**
     * @param MediaManager $mediaManager
     */
    public function setMediaManager($mediaManager)
    {
        $this->mediaManager = $mediaManager;
    }

    /**
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * @param string $rootDir
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = $rootDir;
    }
}
