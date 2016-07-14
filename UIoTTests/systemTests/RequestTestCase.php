<?php

namespace UIoTTests\systemTests;

use UIoTTests\systemTests\httpInterfacer\httpInterfacer;

/**
 * Models a request in this project's testing context.
 * 
 * Class RequestTestCase
 * @package UIoTTests\systemTests
 */
class RequestTestCase
{

    /**
     * The http method used in this request. At the current version this can be GET, POST, PUT or DELETE.
     * 
     * @var string
     */
    private $method;

    /**
     * The http uri used in this request.
     * 
     * @var string
     */
    private $uri;

    /**
     * The http body used in this request. Should contain a Json
     * 
     * @var string
     */
    private $body;

    /**
     * The expected return from executing this request, to be compared with the actual result for asserting whether this
     * test case passed or failed. Should contain a Json.
     * 
     * @var string
     */
    private $expected;

    /**
     * Var for checking whether this request has executed the function selfExecute().
     * It does NOT mean this request has been executed successfully.
     * 
     * @var boolean
     */
    private $wasExecuted;

    /**
     * RequestTestCase constructor.
     * 
     * @param $method
     * @param $uri
     * @param $body
     * @param $expected
     */
    public function __construct($method, $uri, $body, $expected)
    {
        $this->setMethod  ($method);
        $this->setUri     ($uri);
        $this->setBody    ($body);
        $this->setExpected($expected);
        $this->setExecuted(false);
    }

    /**
     * Uses the HttpInterfacer class to prepare and send this test case as an actual request.
     * Marks the executed var as true for debugging purposes in the current version.
     * 
     * @return \Httpful\Response
     */
    public function selfExecute()
    {
        $response = httpInterfacer::sendHttpRequest($this);
        $this->setExecuted(true);
        return $response;
    }
    
    /*
     * Below are default getters & setters
     *----------------------------------------------
     * */

    /**
     * @return mixed
     */
    public function wasExecuted()
    {
        return $this->wasExecuted;
    }

    /**
     * @param mixed $wasExecuted
     */
    public function setExecuted($wasExecuted)
    {
        $this->wasExecuted = $wasExecuted;
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