<?php

namespace Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Transport;

use Actualys\Bundle\DrupalCommerceConnectorBundle\Webservice\Exception\RestConnectionException;
use Guzzle\Http\Client;
use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;
use Guzzle\Plugin\Cookie\CookiePlugin;

class DrupalRestClient
{
    /** @const  string */
    const REST_URL_USER_LOGIN = 'user/login';

    /** @const  string */
    const REST_URL_USER_LOGOUT = 'user/logout';

    /** @const  string */
    const REST_URL_USER_TOKEN = 'user/token';

    /** @var  Client */
    protected $client;

    /** @var  array */
    protected $parameters;

    /** @var  bool */
    protected $connected;

    /**
     * @throws RestConnectionException
     */
    public function __construct()
    {
        $this->client     = null;
        $this->parameters = array();
        $this->connected  = false;
    }

    /**
     *
     */
    public function __destruct()
    {
       $this->logout();
    }

    /**
     * @param  array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        // Close any previous opened connection.
        $this->logout();

        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @param  string $method
     * @param  array  $data
     *
     * @return mixed
     */
    public function call($method, $data = array())
    {
        $this->login();

        $response = $this->remoteCall(
          $this->parameters['endpoint'].'/'.$this->parameters['resource_path'].'/'.$method,
          array('X-CSRF-Token' => $this->generateCsrfToken()),
          $data
        );

        return $response;
    }

    /**
     * @return \Guzzle\Http\Client
     */
    protected function getClient()
    {
        if (is_null($this->client)) {
            $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
            $this->client = new Client($this->parameters['base_url']);
            $this->client->addSubscriber($cookiePlugin);
        }

        return $this->client;
    }

    /**
     * @return string
     */
    protected function generateCsrfToken()
    {
        $request = $this->getClient()->post(
          $this->parameters['endpoint'].'/'.self::REST_URL_USER_TOKEN,
          array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
          ),
          json_encode(array())
        )->send();

        $response = json_decode($request->getBody(true));

        if (empty($response)) {
            throw new BadResponseException('Error while retrieving CSRF Token.');
        }

        return $response->token;
    }

    /**
     * @param string $uri
     * @param array  $headers
     * @param array  $data
     *
     * @return mixed
     */
    protected function remoteCall($uri, $headers = array(), $data = array())
    {
        $headers += array(
          'Accept'       => 'application/json',
          'Content-Type' => 'application/json',
        );

        $request = $this->getClient()->post(
          $uri,
          $headers,
          json_encode($data)
        )->send();

        $response = json_decode($request->getBody(true));

        if ($response === false) {
            throw new BadResponseException('Bad response.');
        }

        return $response;
    }

    /**
     * @throws RestConnectionException
     */
    protected function login()
    {
        if (!$this->connected) {
            $this->remoteCall(
              $this->parameters['endpoint'].'/'.self::REST_URL_USER_LOGIN,
              array('X-CSRF-Token' => $this->generateCsrfToken()),
              array(
                'username' => $this->parameters['user'],
                'password' => $this->parameters['pwd'],
              )
            );

            $this->connected = true;
        }
    }

    /**
     *
     */
    protected function logout()
    {
        if ($this->client && $this->connected) {
            $this->remoteCall(
              $this->parameters['endpoint'].'/'.self::REST_URL_USER_LOGOUT,
              array('X-CSRF-Token' => $this->generateCsrfToken())
            );
        }

        $this->client    = null;
        $this->connected = false;
    }
}
