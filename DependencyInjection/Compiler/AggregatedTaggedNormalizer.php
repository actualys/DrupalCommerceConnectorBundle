<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler;

/**
 * Class AggregatedTaggedNormalizer
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\DependencyInjection\Compiler
 */
class AggregatedTaggedNormalizer
{
    /** @var array */
    private $chain;

    /**
     *
     */
    public function __construct()
    {
        $this->chain = array();
    }

    /**
     * @param mixed  $service
     * @param string $alias
     */
    public function addNormalizer($service, $alias)
    {
        $this->chain[$alias] = $service;
    }

    /**
     * @param string $alias
     *
     * @return mixed
     */
    public function getNormalizer($alias)
    {
        if (array_key_exists($alias, $this->chain)) {
            return $this->chain[$alias];
        }

        return;
    }
}
