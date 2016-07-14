<?php

namespace UIoTTests\systemTests\httpInterfacer;

use UIoTTests\systemTests\RequestTestCase;
use Httpful;
use Httpful\Request;

/**
 * Organizes the use of library Httpful in this project. With one public function this class handles RequestTestCase
 * objects to send them as requests with their respective methods and attributes.
 * 
 * Class httpInterfacer
 * @package UIoTTests\systemTests\httpInterfacer
 */
class httpInterfacer
{

    /**
     * Generic alias to route requests by method.
     * 
     * @param RequestTestCase $requestTestCase
     * @return Httpful\Response
     */
    public static function sendHttpRequest(RequestTestCase $requestTestCase)
    {
        switch ($requestTestCase->getMethod())
        {
            case "get":{
                return HttpInterfacer::sendHttpGetRequest($requestTestCase->getUri());
            }
            case "post":{
                return HttpInterfacer::sendHttpPostRequest($requestTestCase->getUri(), $requestTestCase->getBody());
            }
            case "put":{
                return HttpInterfacer::sendHttpPutRequest($requestTestCase->getUri(), $requestTestCase->getBody());
            }
            case "delete":{
                return HttpInterfacer::sendHttpDeleteRequest($requestTestCase->getUri(), $requestTestCase->getBody());
            }
        }
    }

    /**
     * The functions below serve the request's method properly according to the Httpful library.
     * ----------------------------------------------------------------------------------------------
     */

    /**
     * @param $uri
     * @return Httpful\Response
     */
    private static function sendHttpGetRequest($uri)
    {
        $request = Request::get()
            ->sends(Httpful\Mime::JSON)
            ->uri($uri);
        
        return $request->send();
    }

    /**
     * @param $uri
     * @param $body
     * @return Httpful\Response
     */
    private static function sendHttpPutRequest($uri, $body)
    {
        $request = Request::put()
            ->sends(Httpful\Mime::JSON)
            ->uri($uri)
            ->body($body);
    
        return $request->send();
    }

    /**
     * @param $uri
     * @param $body
     * @return Httpful\Response
     */
    private static function sendHttpPostRequest($uri, $body)
    {
        $request = Request::post()
            ->sends(Httpful\Mime::JSON)
            ->uri($uri)
            ->body($body);
        
        return $request->send();
    }

    /**
     * @param $uri
     * @param $body
     * @return Httpful\Response
     */
    private static function sendHttpDeleteRequest($uri, $body)
    {
        $request = Request::delete()
            ->sends(Httpful\Mime::JSON)
            ->uri($uri)
            ->body($body);


        return $request->send();
    }
}