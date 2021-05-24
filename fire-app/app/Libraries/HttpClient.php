<?php

namespace App\Libraries;

use GuzzleHttp\Client;

class HttpClient
{
    /**
     * HTTP Header
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Base URI
     *
     * @var string
     */
    protected $baseUri = "";

    /**
     * URI
     *
     * @var string
     */
    protected $uri = "";

    /**
     * HTTP Method
     *
     * @var string
     */
    protected $method;

    /**
     * Parameters
     *
     * @var array
     */
    protected $params = [];

    /**
     * Set HTTP Header
     *
     * @param array $headers Http headers
     *
     * @return $this
     */
    public function setHeader(array $headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Set Base URI
     *
     * @param string $url Base URI
     *
     * @return $this
     */
    public function setBaseUri(string $baseUri)
    {
        $this->baseUri = $baseUri;
        return $this;
    }

    /**
     * Set URI
     *
     * @param string $uri URI
     *
     * @return $this
     */
    public function setUri(string $uri)
    {
        $this->uri = $uri;
        return $this;
    }

    /**
     * Get URI
     *
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Get Base URI
     *
     * @return string
     */
    public function getBaseUri()
    {
        if (empty($this->baseUri)) {
            throw new Exception("No Uri");
        }
        return $this->baseUri;
    }

    /**
     * Get Http header
     *
     * @return array
     */
    public function getHeader()
    {
        return $this->headers;
    }

    /**
     * Set Method
     *
     * @param string $method Http Method
     *
     * @return $this
     */
    public function setMethod(string $method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * Get Http Method
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Set Parameters
     *
     * @param array $uri Paramas
     *
     * @return $this
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Get params
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Call API
     *
     * @return array|null
     */
    public function call()
    {   
        $params = $this->getParams();

        logger("[HTTPClient] {$this->getUri()} => {$this->getMethod()} / ".json_encode($params));
        $client = new Client([
            'base_uri' => $this->getBaseUri(),
            'headers' => $this->getHeader(),
        ]);

        $response = $client->request(
            $this->getMethod(),
            $this->getUri(),
            $this->getParams()
        );

        return [
            'code' => $response->getStatusCode(),
            'content' => json_decode($response->getBody()->getContents(), true)
        ];
    }

    /**
     * Prepare Client request
     *
     * @param  string $method Http method
     * @param  string $uri URI
     * @param  array  $params Parameters
     *
     * @return void
     */
    public function prepareRequest(string $method, string $uri, array $params)
    {
        $this->setMethod($method);
        $this->setUri($uri);
        $this->setParams($params);
    }

    /**
     * Call API by POST method
     *
     * @param  string $uri    URI
     * @param  array  $params Params
     *
     * @return array|null
     */
    public function post(string $uri, array $params)
    {
        $this->prepareRequest('POST', $uri, ['json' => $params]);
        return $this->call();
    }

    /**
     * Call API by PUT method
     *
     * @param  string $uri    URI
     * @param  array  $params Params
     *
     * @return array|null
     */
    public function put(string $uri, array $params)
    {
        $this->prepareRequest('PUT', $uri, ['json' => $params]);
        return $this->call();
    }

    /**
     * Call API by GET method
     *
     * @param  string $uri    URI
     * @param  array  $params Params
     *
     * @return array|null
     */
    public function get(string $uri, array $params = [])
    {
        $this->prepareRequest('GET', $uri, ['query' => $params]);
        return $this->call();
    }

    /**
     * Call API by DELETE method
     *
     * @param  string $uri    URI
     * @param  array  $params Params
     *
     * @return array|null
     */
    public function delete(string $uri, array $params = [])
    {
        $this->prepareRequest('DELETE', $uri, ['query' => $params]);
        return $this->call();
    }
}
