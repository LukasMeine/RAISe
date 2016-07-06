<?php

namespace UIoTRaiseTestes\systemTests;

use Exception;
use Httpful\Request;

/**
 * Class RequestTestCase
 * @package UIoTRaiseTestes\systemTests
 */
class RequestTestCase
{
    private $name;
    private $method;
    private $uri;
    private $body; /* json */
    private $expected; /* json */

    public function __construct($name, $method, $uri, $body, $expected)
    {
        $this->setName($name);
        $this->setMethod($method);
        $this->setUri($uri);
        $this->setBody($body);
        $this->setExpected($expected);
    }

    public function sendRequest()
    {
       switch ($this->method){
           case "GET": return $this->sendGetRequest();
           case "POST": return $this->sendPostRequest();
           case "PUT": return $this->sendPutRequest();
       }
        throw new Exception("Division by zero.");
    }

    /**
     * interfaces raise tests and Httpful lib for method GET
     * @return mixed
     */
    private function sendGetRequest()
    {
        return Request::get( $this->getUri() )->send();
    }

    /**
     * interfaces raise tests and Httpful lib for method POST
     * @return mixed
     */
    private function sendPostRequest()
    {
        return Request::post( $this->getUri())
            ->body( $this->getBody())
            ->sendsJson() //todo check, originally '->sendsXml()'
            ->send();
    }
    
    /**
     * interfaces raise tests and Httpful lib for method PUT
     * @return mixed
     */
    private function sendPutRequest()
    {
        return Request::put( $this->getUri() )
            ->body( $this->getBody() )
            ->sendsJson() //todo check, originally '->sendsXml()'
            ->send();
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

  
    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }

    /**
     * @return mixed
     */
    public function getExpected()
    {
        return $this->expected;
    }

    /**
     * @param mixed $expected
     */
    public function setExpected($expected)
    {
        $this->expected = $expected;
    }
}