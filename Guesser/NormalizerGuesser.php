<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Guesser;

use Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler\AggregatedTaggedNormalizer;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Guesser\Exception\GuessException;

class NormalizerGuesser
{
    /** @var AggregatedTaggedNormalizer */
    protected $normalizers;

    /**
     * @param AggregatedTaggedNormalizer $normalizer
     */
    public function __construct(
      AggregatedTaggedNormalizer $normalizer
    ) {
        $this->normalizers = $normalizer;
    }

    /**
     * @param $type
     * @param $normalizeType
     * @return mixed
     * @throws GuessException
     */
    public function guessNormalizer($type, $normalizeType)
    {
        $normalizer = $this->normalizers->getNormalizer(
          $type.'.'.$normalizeType
        );
        if (is_null($normalizer)) {
            throw new GuessException(
              'Type de normalizer non géré: '.$normalizeType.' pour '.$type
            );
        }

        return $normalizer;
    }
}
