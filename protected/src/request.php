<?php

class Request
{
    /**
     * @var array
     */
    public $urlElements;

    /**
     * @var string
     */
    public $requestMethod;

    /**
     * @var array
     */
    public $parameters;

    /**
     * @var bool
     */
    public $isJson;

    public function __construct()
    {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        if (isset($_SERVER['PATH_INFO'])) {
            $this->urlElements = explode('/', $_SERVER['PATH_INFO']);
        } else {
            $this->urlElements = array_filter(
                explode('/', str_replace('?' . $_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI'])));
        }
        $this->parseIncomingParams();
        return true;
    }

    /**
     * Parse incoming parameters
     */
    public function parseIncomingParams()
    {
        $parameters = array();

        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }

        $body = file_get_contents("php://input");
        $contentType = false;
        if (isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        }

        $bodyParams = (!empty($body)) ? json_decode($body) : false;
        if ($contentType == 'application/json' && !is_null($bodyParams)) {
            $parameters = json_decode($body, true);
            $this->isJson = true;
        } else {
            $this->isJson = false;
        }

        $this->parameters = $parameters;
    }
}