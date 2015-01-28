<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Pim\Bundle\CatalogBundle\Entity\Group;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GroupNormalizer implements NormalizerInterface
{
    /**
     * @param  object                                                $group
     * @param  null                                                  $format
     * @param  array                                                 $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public function normalize($group, $format = null, array $context = [])
    {
        /**@var Group $group * */
        $normalizedGroup = [
          'code' => $group->getCode(),
          'type' => $group->getType()->getCode(),

        ];
        foreach ($group->getTranslations() as $trans) {
            $normalizedGroup['labels'][$trans->getLocale()] = $trans->getLabel(
            );
        }
        foreach ($group->getAttributes() as $attr) {
            $normalizedGroup['attributes'][] = $attr->getCode();
        }

        return $normalizedGroup;
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
        return $data instanceof Group;
    }
}
