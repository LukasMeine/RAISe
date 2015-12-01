<?php

namespace UIoT\communication;

include_once ROOT_REST_DIR . "/util/socket.helper.php";
include_once ROOT_REST_DIR . "/util/restRouter.helper.php";
include_once ROOT_REST_DIR . "/properties/socket.properties.php";

/**
 * Class ExternalCommunicator
 */
final class ExternalCommunicator
{
    var $socket;
    var $rest_router;

    public function __construct($port, $address)
    {
        self::start_socket($port, $address);
        self::start_rest_router();
    }

    public function start_socket($port, $address)
    {
        $this->socket = new Socket($port, $address);
    }

    public function start_rest_router()
    {
        $this->rest_router = new RestRouter();
    }

    public function submit_request($request)
    {
        $json_response = self::submit_request_to_router($request);

        if ($request->get_type() == "PUT") {
            $this->socket->send_data($json_response);
        }

        return $json_response;
    }

    private function submit_request_to_router($request)
    {
        return $this->rest_router->submit_request($request);
    }
}