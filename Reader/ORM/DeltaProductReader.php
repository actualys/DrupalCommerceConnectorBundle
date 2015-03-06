<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Reader\ORM;

use Pim\Bundle\BaseConnectorBundle\Reader\Doctrine\ObsoleteProductReader as PimProductReader;
use Pim\Bundle\BaseConnectorBundle\Reader\Doctrine\Reader;
use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Pim\Bundle\TransformBundle\Converter\MetricConverter;
use Pim\Bundle\CatalogBundle\Manager\ChannelManager;
use Pim\Bundle\CatalogBundle\Manager\CompletenessManager;
use Pim\Bundle\CatalogBundle\Repository\ProductRepositoryInterface;
use Pim\Bundle\CatalogBundle\Model\AbstractProduct;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Manager\ProductExportManager;

class DeltaProductReader extends PimProductReader
{
    protected $productExportManager;

    /**
     * Constructor
     *
     * @param ProductRepositoryInterface $repository
     * @param ChannelManager             $channelManager
     * @param CompletenessManager        $completenessManager
     * @param MetricConverter            $metricConverter
     * @param ProductExportManager       $productExportManager
     */
    public function __construct(
        ProductRepositoryInterface $repository,
        ChannelManager $channelManager,
        CompletenessManager $completenessManager,
        MetricConverter $metricConverter,
        ProductExportManager $productExportManager
    ) {
        parent::__construct($repository, $channelManager, $completenessManager, $metricConverter);

        $this->productExportManager = $productExportManager;
    }

    /**
     * {@inheritdoc}
     */
    public function read()
    {
        $product = $this->filterProduct(parent::read());

        return $product;
    }

    /**
     * Filter products and return only products that got updated since the last export
     * @param AbstractProduct $readProduct
     *
     * @return AbstractProduct|null
     */
    protected function filterProduct(AbstractProduct $readProduct = null)
    {
        if (null !== $readProduct) {
            $filteredProduct = $this->productExportManager->filterProduct($readProduct, $this->jobInstance);

            if ($filteredProduct === null) {
                return $this->filterProduct(parent::read());
            } else {
                return $filteredProduct;
            }
        }

        return null;
    }

    /**
     * Update the product export date (will be removed later)
     * @param AbstractProduct $product
     */
    protected function updateProductExport(AbstractProduct $product)
    {
        $this->productExportManager->updateProductExport($product->getIdentifier(), $this->jobInstance);
    }

    /**
     * {@inheritdoc}
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        parent::setStepExecution($stepExecution);

        $this->jobInstance = $this->stepExecution->getJobExecution()->getJobInstance();
    }
}
