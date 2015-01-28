<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Transport;

use Guzzle\Http\Client;
use Guzzle\Http\Exception\RequestException;
use Guzzle\Http\Exception\ServerErrorResponseException;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Http\Exception\BadResponseException;
use Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Exception\RestConnectionException;

class DrupalRestClient
{
    /**
     * @var DrupalRestClient
     * @access private
     * @static
     */
    protected static $instance = null;

    /** @const  string */
    const REST_URL_USER_LOGIN = 'user/login';

    /** @const  string */
    const REST_URL_GET_CSRF_TOKEN = 'user/token';

    /** @var  string */
    protected $csrfToken;

    /** @var array */
    protected $parameters;

    /** @var  Client */
    protected $client;

    /**
     * @throws RestConnectionException
     */
    public function __construct()
    {
        $this->parameters = array();
    }

    /**
     * @param  array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        // Todo: kill any previously created session.

        return $this;
    }

    /**
     * @throws RestConnectionException
     */
    protected function connect()
    {
        if (!$this->csrfToken) {
            try {
                // Session cookie
                $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
                $this->client = new Client($this->parameters['base_url']);
                $this->client->addSubscriber($cookiePlugin);

                $this->client->post(
                  self::REST_URL_USER_LOGIN,
                  array('Content-Type' => 'application/json'),
                  json_encode(
                    array(
                      'username' => $this->parameters['user'],
                      'password' => $this->parameters['pwd'],
                      'form_id'  => 'user_login_form',
                    )
                  )
                )->send();

                // Csrf token
                $this->csrfToken = $this->requestCsrfToken();
            } catch (\Exception $e) {
                throw new RestConnectionException(
                  'Unable to connect to REST server'
                );
            }
        }
    }

    /**
     * @return string
     */
    protected function requestCsrfToken()
    {
        $this->client = new Client(
          $this->parameters['base_url'].'/'.$this->parameters['endpoint']
        );
        $response = $this->client->post(
          self::REST_URL_GET_CSRF_TOKEN,
          array('Content-Type' => 'application/json'),
          json_encode(array())
        )->send();
        $responseAsString = $response->getBody(true);

        if (empty($responseAsString)) {
            throw new BadResponseException('CSRF token recovery problem');
        }

        return $response->getBody(true);
    }

    /**
     * @param  string                              $method
     * @param  array                               $data
     * @return \Guzzle\Http\Message\Response|mixed
     */
    public function call($method, $data = array())
    {
        try {
            $this->connect();

            $response = $this->client->post(
              $this->parameters['resource_path'].'/'.$method,
              array(
                'Content-Type' => 'application/json',
                'X-CSRF-Token' => $this->csrfToken,
              ),
              json_encode($data)
            )->send();
        } catch (ServerErrorResponseException $e) {
            $message = $e->getResponse()->getBody(true);

            throw new RequestException(
              $message,
              $e->getResponse()->getStatusCode()
            );
        }

        $response = json_decode($response->getBody(true));

        if (empty($response)) {
            throw new RequestException('Invalid JSON Response');
        }

        return $response;
    }
}
