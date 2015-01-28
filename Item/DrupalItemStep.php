<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Item;

use Akeneo\Bundle\BatchBundle\Entity\StepExecution;
use Symfony\Component\Validator\Constraints as Assert;
use Akeneo\Bundle\BatchBundle\Item\AbstractConfigurableStepElement;
use Akeneo\Bundle\BatchBundle\Step\StepExecutionAwareInterface;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Webservice;

/**
 * Class DrupalItemStep
 *
 * @package Actualys\Bundle\DrupalCommerceConnectorBundle\Item
 *
 */
abstract class DrupalItemStep extends AbstractConfigurableStepElement implements StepExecutionAwareInterface
{
    /** @var StepExecution */
    protected $stepExecution;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Execution"})
     */
    protected $baseUrl;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Execution"})
     */
    protected $endpoint;

    /**
     * @var string
     * @Assert\NotBlank(groups={"Execution"})
     */
    protected $resourcePath;

    /**
     * @var string Http login
     * @Assert\NotBlank(groups={"Execution"})
     */
    protected $httpLogin;

    /**
     * @var string Http password
     * @Assert\NotBlank(groups={"Execution"})
     */
    protected $httpPassword;

    /**
     * @var Webservice webservice
     */
    protected $webservice;

    /**
     * Function called before all item step execution
     */
    public function initialize()
    {
        if ($this->webservice) {
            $this->webservice->setParameters($this->getClientParameters());
        }
    }

    /**
     * @param Webservice $webservice
     */
    public function setWebservice(Webservice $webservice)
    {
        $this->webservice = $webservice;
    }

    /**
     * @return Webservice
     */
    public function getWebservice()
    {
        return $this->webservice;
    }

    /**
     * Get the drupal rest client parameters
     *
     * @return array
     */
    protected function getClientParameters()
    {
        return [
          'base_url'      => $this->baseUrl,
          'endpoint'      => $this->endpoint,
          'resource_path' => $this->resourcePath,
          'user'          => $this->httpLogin,
          'pwd'           => $this->httpPassword,
        ];
    }

    /**
     * Get fields for the twig
     *
     * @return array
     */
    public function getConfigurationFields()
    {
        return [
          'baseUrl'      => [
            'options' => [
              'required' => true,
              'help'     => 'actualys_drupal_commerce_connector.export.baseUrl.help',
              'label'    => 'actualys_drupal_commerce_connector.export.baseUrl.label',
            ],
          ],
          'endpoint'     => [
            'options' => [
              'required' => true,
              'help'     => 'actualys_drupal_commerce_connector.export.endpoint.help',
              'label'    => 'actualys_drupal_commerce_connector.export.endpoint.label',
            ],
          ],
          'resourcePath' => [
            'options' => [
              'required' => true,
              'help'     => 'actualys_drupal_commerce_connector.export.resourcePath.help',
              'label'    => 'actualys_drupal_commerce_connector.export.resourcePath.label',
            ],
          ],
          'httpLogin'    => [
            'options' => [
              'required' => false,
              'help'     => 'actualys_drupal_commerce_connector.export.httpLogin.help',
              'label'    => 'actualys_drupal_commerce_connector.export.httpLogin.label',
            ],
          ],
          'httpPassword' => [
            'options' => [
              'required' => false,
              'help'     => 'actualys_drupal_commerce_connector.export.httpPassword.help',
              'label'    => 'actualys_drupal_commerce_connector.export.httpPassword.label',
            ],
          ]
        ];
    }

    /**
     * @return string
     */
    public function getHttpLogin()
    {
        return $this->httpLogin;
    }

    /**
     * @param string $httpLogin
     */
    public function setHttpLogin($httpLogin)
    {
        $this->httpLogin = $httpLogin;
    }

    /**
     * @return string
     */
    public function getHttpPassword()
    {
        return $this->httpPassword;
    }

    /**
     * @param string $httpPassword
     */
    public function setHttpPassword($httpPassword)
    {
        $this->httpPassword = $httpPassword;
    }

    /**
     * @param StepExecution $stepExecution
     */
    public function setStepExecution(StepExecution $stepExecution)
    {
        $this->stepExecution = $stepExecution;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @return string
     */
    public function getResourcePath()
    {
        return $this->resourcePath;
    }

    /**
     * @param string $resourcePath
     */
    public function setResourcePath($resourcePath)
    {
        $this->resourcePath = $resourcePath;
    }

    /**
     * @return string
     */
    public function getEndpoint()
    {
        return $this->endpoint;
    }

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint)
    {
        $this->endpoint = $endpoint;
    }
}
