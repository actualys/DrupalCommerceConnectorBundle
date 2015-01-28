<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Controller\Rest;

use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations\NamePrefix;
use Pim\Bundle\CatalogBundle\Manager\MediaManager;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;
use Pim\Bundle\CatalogBundle\Model\Product;
use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Pim\Bundle\CatalogBundle\Entity\Repository\AttributeRepository;
use FOS\RestBundle\Controller\Annotations\Get;
use Pim\Bundle\UserBundle\Context\UserContext;
use Pim\Bundle\CatalogBundle\Model\ProductValue;
use Pim\Bundle\CatalogBundle\Model\ProductMedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @NamePrefix("akeneo_api_")
 */
class MediaController extends Controller
{

    /**
     * Get a single product
     *
     * @Get("/{sku}/{attribute_id}")
     *
     * @param  string   $sku
     * @param  string   $attribute_id
     * @return Response
     */
    public function getAction($sku, $attribute_id)
    {
        /** @var UserContext $userContext */
        //$userContext = $this->get('pim_user.context.user');

        return $this->handleGetRequest($sku, $attribute_id);
    }

    /**
     * @param $identifier
     * @param $attribute_id
     * @return Response
     */
    protected function handleGetRequest($identifier, $attribute_id)
    {
        $productManager   = $this->container->get(
          'pim_catalog.manager.product'
        );
        $attributeManager = $this->container->get(
          'pim_catalog.manager.attribute'
        );

        /** @var  Product $product */
        /** @var ProductManager $productManager */
        $product = $productManager->findByIdentifier($identifier);

        /** @var Attribute $attribute */
        $attributeRepository = $this->container->get(
          'pim_catalog.repository.attribute'
        );

        /** @var AttributeRepository $attributeRepository */
        $attribute = $attributeRepository->findOneBy(
          array('id' => $attribute_id)
        );

        /** @var ProductValue $productValue */
        $productValue = $product->getValue($attribute->getCode());
        $data         = $this->getSerializedData(
          $productValue,
          $attribute->getCode()
        );

        return new Response($data);
    }

    /**
     * @param  ProductValue $productValue
     * @param $attribute
     * @return string
     */
    public function getSerializedData(ProductValue $productValue, $attribute)
    {
        /** @var ProductMedia $media */
        $media = $productValue->getMedia();

        /** @var MediaManager $mediaManager */
        $mediaManager = $this->get('pim_catalog.manager.media');

        /** @var ProductValue $productValue */
        $info_media                   = new \stdClass();
        $info_media->content          = $mediaManager->getBase64($media);
        $info_media->created          = $productValue->getAttribute()
          ->getCreated()
          ->getTimestamp();
        $info_media->updated          = $productValue->getAttribute()
          ->getUpdated()
          ->getTimestamp();
        $info_media->filename         = $media->getFilename();
        $info_media->filepath         = $media->getFilePath();
        $info_media->originalFilename = $media->getOriginalFilename();
        $info_media->mimeType         = $media->getMimeType();
        $info_media->size             = filesize($media->getFilePath());
        $data                         = json_encode($info_media);

        return $data;
    }
}
