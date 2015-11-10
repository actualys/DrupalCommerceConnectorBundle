<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Processor;

use Akeneo\Bundle\BatchBundle\Item\InvalidItemException;
use Akeneo\Bundle\BatchBundle\Item\ItemProcessorInterface;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Akeneo\Bundle\BatchBundle\Item\AbstractConfigurableStepElement;
use PimEnterprise\Bundle\WorkflowBundle\Manager\PublishedProductManager;
use Doctrine\ORM\EntityManager;


class PublishedProductProcessor extends AbstractConfigurableStepElement implements ItemProcessorInterface
{

    /** @var  PublishedProductManager $productMassActionRepository */
    protected $publishedProductManager;

    /** @var   EntityManager $entityManager **/
    protected $entityManager;
    /**
     * @param ProductManager    $productManager
     */
    public function __construct(
      PublishedProductManager $publishedProductManager,
      EntityManager $entityManager
    ) {
        $this->publishedProductManager    = $publishedProductManager;
        $this->entityManager    = $entityManager;
    }

    /**
     * @param  mixed  $products
     * @return mixed
     * @throws InvalidItemException
     */
    public function process($publishedProductIds)
    {
      $this->entityManager->clear();
          $publishedProducts = $this->entityManager->getRepository('PimEnterprise\Bundle\WorkflowBundle\Model\PublishedProduct')->findByIds($publishedProductIds);

        return $this->publishProducts($publishedProducts);

    }

    /**
     * Normalize the given product
     *
     * @param ProductInterface $product [description]
     *
     * @throws InvalidItemException If a normalization error occurs
     *
     * @return mixed processed item
     */
    protected function publishProducts($publishedProducts)
    {

       $originalProducts = array_map(
         function ($item) {
          return $item->getOriginalProduct();
           },
            $publishedProducts
         );
        $ids = array();
        foreach ($originalProducts as $originalProduct) {
          $originalProduct->__load();

          if ($originalProduct->getId() == 4881) {
            echo 'test';
          }

          try {
            $this->publishedProductManager->publish($originalProduct, ['with_associations' => false]);
            $ids[] = $originalProduct;

            file_put_contents('succes_publish', $originalProduct->getId(), FILE_APPEND);

          }
          catch (\Exception $e) {

            ob_start();
            var_dump($e);
            $var = ob_get_clean();

              file_put_contents('erreur_publish', $originalProduct->getId() . " " .$var, FILE_APPEND);
          }
        }
        return $ids;
    }


    /**
     * Get fields for the twig
     *
     * @return array
     */
    public function getConfigurationFields() {
        return array();
    }
}
