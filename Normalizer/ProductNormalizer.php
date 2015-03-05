<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Pim\Bundle\CatalogBundle\Entity\Category;
use Pim\Bundle\CatalogBundle\Model\Product;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Guesser\NormalizerGuesser;
use Pim\Bundle\CatalogBundle\Manager\ChannelManager;
use Pim\Bundle\CatalogBundle\Model\ProductInterface;
use Pim\Bundle\CatalogBundle\Entity\Channel;
use Pim\Bundle\CatalogBundle\Entity\Repository\CategoryRepository;
use Pim\Bundle\CatalogBundle\Entity\Repository\GroupRepository;
use Pim\Bundle\CatalogBundle\Entity\Group;
use Pim\Bundle\CatalogBundle\Manager\ProductManager;

class ProductNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerGuesser $normalizerGuesser
     */
    protected $normalizerGuesser;


    /** @var CategoryRepository $categoryRepository */
    protected $categoryRepository;

    /** @var ChannelManager $channelManager */
    protected $channelManager;

    /** @var ProductManager $channelManager */
    protected $productManager;

    /** @var Array $rootCategories */
    protected $rootCategories;

    /** @var Array $formatedRootCategories */
    protected $formatedRootCategories;

    /**
     * @param ChannelManager    $channelManager
     * @param NormalizerGuesser $normalizerGuesser
     */
    public function __construct(
      ChannelManager $channelManager,
      NormalizerGuesser $normalizerGuesser,
      CategoryRepository $categoryRepository,
      ProductManager $productManager
    ) {
        $this->channelManager     = $channelManager;
        $this->productManager     = $channelManager;
        $this->normalizerGuesser  = $normalizerGuesser;
        $this->categoryRepository = $categoryRepository;
        $this->productManager     = $productManager;

        if (empty($this->formatedRootCategories)) {
            $this->rootCategories = $this->categoryRepository->getRootNodes();
            foreach ($this->rootCategories as $rootCategory) {
                $this->formatedRootCategories[$rootCategory->getId(
                )] = $rootCategory->getCode();
            }
        }
    }

    /**
     * @param  object                                                $product
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws NormalizeException
     */
    public function normalize($product, $format = null, array $context = [])
    {
        $drupalProduct = $this->getDefaultDrupalProduct($product);
        $this->computeProductCategory($product, $drupalProduct);
        $this->computeProductGroup($product, $drupalProduct);
        $this->computeProductAssociation($product, $drupalProduct);
        $this->computeProductValues(
          $product,
          $drupalProduct,
          $context['channel'],
          $context['configuration']
        );

        return $drupalProduct;
    }

    /**
     * @param  $product
     * @return array
     */
    public function getDefaultDrupalProduct(ProductInterface $product)
    {
        $labels           = [];
        $attributeAsLabel = $product->getFamily()->getAttributeAsLabel();
        $availableLocales = $attributeAsLabel->getAvailableLocales();
        if (!is_null($availableLocales)) {
            foreach ($availableLocales as $availableLocale) {
                $labels[$availableLocale->getCode()] = $product->getLabel(
                  $availableLocale->getCode()
                );
            }
        }

        // TODO: fix availableLocales doesn t must be NULL
        foreach ($attributeAsLabel->getTranslations() as $translation) {
            $labels[$translation->getLocale()] = $product->getLabel(
              $translation->getLocale()
            );
        }

        $defaultDrupalProduct = [
          'sku'        => $product->getReference(),
          'family'     => $product->getFamily()->getCode(),
          'created'    => $product->getCreated()->format('c'),
          'updated'    => $product->getUpdated()->format('c'),
          'status'     => $product->isEnabled(),
          'labels'     => $labels,
          'categories' => [],
          'groups' => [],
          'associations' => [],
          'values'     => [],
        ];

        return $defaultDrupalProduct;
    }

    /**
     * @param ProductInterface $product
     * @param array            $drupalProduct
     */
    protected function computeProductGroup(
      ProductInterface $product,
      array &$drupalProduct
    ) {

       /** @var Group $group */
       foreach ($product->getGroups() as $group) {
           $drupalProduct['groups'][$group->getType()->getCode()]['code'] = $group->getCode();
       }
    }

    /**
     * @param ProductInterface $product
     * @param array            $drupalProduct
     */
    protected function computeProductAssociation(
      ProductInterface $product,
      array &$drupalProduct
    ) {

        $identifierAttributeCode = $this->productManager->getIdentifierAttribute(
        )->getCode();
       /** @var Group $group */
       foreach ($product->getAssociations() as $association) {
           $associationCode = $association->getAssociationType()->getCode();

           if ($association->getGroups()->count(
             ) > 0 || $association->getProducts()->count() > 0
           ) {

               /**@var Product $product * */
               $drupalProduct['associations'][$associationCode] = [
                 'type'     => null,
                 'groups'   => [],
                 'products' => [],
               ];

               $drupalProduct['associations'][$associationCode]['type'] = $associationCode;
               foreach ($association->getGroups() as $group) {
                   $drupalProduct['associations'][$associationCode]['groups'][] = $group->getCode(
                   );
               }
               foreach ($association->getProducts() as $product) {
                   $drupalProduct['associations'][$associationCode]['products'][] = $product->getValue(
                     $identifierAttributeCode
                   )->getData();
               }
           }
       }
    }

    /**
     * @param ProductInterface $product
     * @param array            $drupalProduct
     */
    protected function computeProductCategory(
      ProductInterface $product,
      array &$drupalProduct
    ) {
        /** @var Category $category */
        foreach ($product->getCategories() as $category) {
            if ($category->getLevel() > 0) {
                $drupalProduct['categories'][$this->formatedRootCategories[$category->getRoot(
                )]][] = $category->getCode();
            }
        }
    }

    /**
     * @param ProductInterface $product
     * @param array            $drupalProduct
     * @param Channel          $channel
     * @param array            $configuration
     *
     * @throws \Exception
     */
    protected function computeProductValues(
      ProductInterface $product,
      array &$drupalProduct,
      Channel $channel,
      $configuration
    ) {

        /** @var \Pim\Bundle\CatalogBundle\Model\ProductValue $value */
        foreach ($product->getValues() as $value) {

            /*
            // Skip out of scope values or not global ones.
            if ($value->getScope() != $channel->getCode() && $value->getScope(
              ) !== null
            ) {
                continue;
            }*/

            $field  = $value->getAttribute()->getCode();
            $type   = $value->getAttribute()->getAttributeType();
            $locale = $value->getLocale();
            if (is_null($locale)) {
                $locale = LANGUAGE_NONE;
            }
            $labelAttribute = $value->getEntity()
              ->getFamily()
              ->getAttributeAsLabel()
              ->getCode();
            
            if ($type == 'pim_catalog_identifier' /* || $field == $labelAttribute*/) {
                continue;
            }

            // Setup default locale.
            $context = [
              'locale'        => $locale,
              'scope'         => $value->getScope(),
              'defaultLocale' => 'fr_FR',
              'defaultLocale' => 'fr_FR',
              'configuration' => $configuration,
            ];

            if ($normalizer = $this->normalizerGuesser->guessNormalizer(
              $type,
              'product_value'
            )
            ) {
                $normalizer->normalize(
                  $drupalProduct,
                  $value,
                  $field,
                  $context
                );
            } else {
                throw new NormalizeException(
                  'Type field not supported: "'.$type.'".',
                  'Normalizing error'
                );
            }
        }
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
