<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer;

use Pim\Bundle\CatalogBundle\Entity\Attribute;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Normalizer\Exception\NormalizeException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Guesser\NormalizerGuesser;

class AttributeNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerGuesser $normalizerGuesser
     */
    protected $normalizerGuesser;

    /**
     * @param NormalizerGuesser $normalizerGuesser
     */
    public function __construct(
      NormalizerGuesser $normalizerGuesser
    ) {
        $this->normalizerGuesser = $normalizerGuesser;
    }

    /**
     * @param  object                                                                          $attribute
     * @param  null                                                                            $format
     * @param  array                                                                           $context
     * @return array|\Symfony\Component\Serializer\Normalizer\scalar
     * @throws NormalizeException
     * @throws \Actualys\Bundle\DrupalCommerceConnectorBundle\Guesser\Exception\GuessException
     */
    public function normalize($attribute, $format = null, array $context = [])
    {
        $type = $attribute->getAttributeType();
        if ($normalizer = $this->normalizerGuesser->guessNormalizer(
          $type,
          'attribute'
        )
        ) {
            $normalizedAttribute = $normalizer->normalize(
              $attribute,
              null,
              $context
            );
        } else {
            throw new NormalizeException(
              'Type field not supported: "'.$type.'".'
            );
        }

        return $normalizedAttribute;
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
        return $data instanceof Attribute;
    }
}
