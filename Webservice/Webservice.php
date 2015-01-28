<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Transport\DrupalRestClient;

/**
 * Class Webservice
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice
 */
class Webservice
{
    const REST_ACTION_SEND_FAMILY = 'family';
    const REST_ACTION_SEND_PRODUCT = 'product';
    const REST_ACTION_SEND_ATTRIBUTE_OPTION = 'option';
    const REST_ACTION_SEND_CATEGORY = 'category';
    const REST_ACTION_SEND_GROUP = 'group';
    const REST_ACTION_SEND_ASSOCIATION = 'association';

    /**
     * @var DrupalRestClient
     */
    protected $drupalRestClient;

    /**
     * @param DrupalRestClient $drupalRestClient
     */
    public function __construct(DrupalRestClient $drupalRestClient)
    {
        $this->drupalRestClient = $drupalRestClient;
    }

    /**
     * @param  array                             $parameters
     * @throws Exception\RestConnectionException
     */
    public function setParameters(array $parameters)
    {
        $this->drupalRestClient->setParameters($parameters);
    }

    /**
     * @param  array                               $family
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendFamily($family)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_FAMILY,
          $family
        );
    }

    /**
     * @param $option
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendAttributeOption($attributeOption)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_ATTRIBUTE_OPTION,
          $attributeOption
        );
    }

    /**
     * @param  array                               $category
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendCategory($category)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_CATEGORY,
          $category
        );
    }

    /**
     * @param  array                               $group
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendGroup($group)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_GROUP,
          $group
        );
    }

    /**
     * @param  array                               $association
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendAssociation($association)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_ASSOCIATION,
          $association
        );
    }

    /**
     * @param  array                               $product
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function sendProduct($product)
    {
        return $this->drupalRestClient->call(
          self::REST_ACTION_SEND_PRODUCT,
          $product
        );
    }
}
