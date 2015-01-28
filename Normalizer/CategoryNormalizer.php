<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Pim\Bundle\CatalogBundle\Entity\Category;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Entity\Repository\CategoryRepository;

class CategoryNormalizer implements NormalizerInterface
{

    protected $categoryRepository;

    public function __construct(
      CategoryRepository $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param  object                                                $category
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize($category, $format = null, array $context = [])
    {
        $normalizedCategory = [
          'code'     => null,
          'labels'   => [],
          'children' => [],
        ];

        $normalizedCategory ['code'] = $category->getCode();
        /**@var Category $category * */
        foreach ($category->getTranslations() as $trans) {
            $normalizedCategory['labels'] [$trans->getLocale(
            )] = $trans->getLabel();
        }

        $childrenIds = $this->categoryRepository->getAllChildrenIds($category);

        foreach ($childrenIds as $childrenId) {
            $children = $this->categoryRepository->find($childrenId);
            foreach ($children->getTranslations() as $trans) {
                $normalizedCategory['children'][$children->getCode(
                )]['labels'] [$trans->getLocale()] = $trans->getLabel();
            }
            $normalizedCategory['children'][$children->getCode(
            )]['parent'] = (!is_null(
              $children->getParent()
            ) ? $children->getParent()->getCode() : null);
        }

        return $normalizedCategory;
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
        return $data instanceof Category;
    }

    public function normalizeChildren($children)
    {
    }
}
