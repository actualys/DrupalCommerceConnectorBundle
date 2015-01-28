<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Pim\Bundle\CatalogBundle\Model\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;

class AssociationNormalizer implements NormalizerInterface
{
    /**
     * @var ProductManager $productManager
     */
    protected $productManager;

    /**
     * @param ProductManager $productManager
     */
    public function __construct(ProductManager $productManager)
    {
        $this->productManager = $productManager;
    }

    /**
     * @param  object                                                $product
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize($product, $format = null, array $context = [])
    {
        $normalizedAssociations  = [];
        $identifierAttributeCode = $this->productManager->getIdentifierAttribute(
        )->getCode();
        foreach ($product->getAssociations() as $association) {
            $associationCode = $association->getAssociationType()->getCode();

            $nb_groups   = $association->getGroups()->count();
            $nb_products = $association->getProducts()->count();
            if ($association->getGroups()->count(
              ) > 0 || $association->getProducts()->count() > 0
            ) {

                /**@var Product $product * */
                $normalizedAssociations[$associationCode] = [
                  'type'     => null,
                  'groups'   => [],
                  'products' => [],
                ];

                $normalizedAssociations[$associationCode]['type'] = $associationCode;
                foreach ($association->getGroups() as $group) {
                    $normalizedAssociations[$associationCode]['groups'][] = $group->getCode(
                    );
                }
                foreach ($association->getProducts() as $product) {
                    $normalizedAssociations[$associationCode]['products'][] = $product->getValue(
                      $identifierAttributeCode
                    )->getData();
                }
            }
        }

        return $normalizedAssociations;
    }

    /**
     * Checks whether the given class is supported for normalization by this normalizer
     *
     * @param mixed  $data   Data to normalize.
     * @param string $format The format being (de-)serialized from or into.
     *
     * @return boolean
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Product;
    }
}
