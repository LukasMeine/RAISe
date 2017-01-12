<?php
namespace Raise\Treaters;

include_once ('Models/Request.php');
include_once ('Treaters/MessageOutPut.php');
include_once ('Controllers/SecurityController.php');
include_once ('Database/QueryGenerator.php');
include_once ('Database/DatabaseParser.php');

use Raise\Models\Request;
use Raise\Treaters\MessageController;
use Raise\Controllers\SecurityController;
use \DatabaseParser;

class RequestTreater
{
    private $requestInfo = array(
        'protocol' => 'SERVER_PROTOCOL',
        'method' => 'REQUEST_METHOD',
        'path' => 'PATH_INFO',
        'query' => 'QUERY_STRING',
        'sender' => 'REMOTE_ADDRESS'
    );
    private $codes = array(
        "protocol" => 505,
        "method" => 405,
        "path" => 400,
        "query" => 400,
        "remote" => 403
    );

    public function execute()
    {
        $request = $this->create();

        $this->validate($request);

        if (!$request->isValid()) {
            return (new MessageController)->messageHttp($request->getReponseCode());
        }

        $a = new SecurityController();
        if ($a->validate($request) === true)
        {
            $generator = new \QueryGenerator();
            $response = $generator->generate($request);
            return $response;
        } else
        {

            return $a->validate($request);
        }
    }
    public function create()
    {
        $request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['SERVER_PROTOCOL'], $_SERVER['SERVER_ADDR'], $_SERVER['REMOTE_ADDR'], $_SERVER['REQUEST_URI'], $_SERVER['QUERY_STRING'], file_get_contents('php://input'));
        return $request;
    }
    private function validate($request)
    {
        $request->setResponseCode(200);
        $request->setValid(true);

        $database = new DatabaseParser($bucket = $request->getpath()[2])->getBucket();

        $query = \CouchbaseN1qlQuery::fromString('SELECT COUNT(`bucket`) FROM metadata WHERE `bucket` = $bucket');

        if($database->query($query, array('bucket' => $bucket))->$1 <= 0) {
            $request->setResponseCode(422);
            $request->setValid(false);

            return;
        }

        $query = \CouchbaseN1qlQuery::fromString('SELECT docValues FROM metadata WHERE `bucket` = $bucket AND `docNme` = $docNme');

        switch($request->getMethod()) {
            case 'GET':
                $parameters = $database->query($query, array('bucket' => $bucket, 'docNme' => 'get_clients_list_required'))->docValues;

                if(!empty(array_diff($request->getParameters(), $parameters))) {
                    $request->setResponseCode(400);
                    $request->setValid(false);

                    return;
                }
            break;
            case 'POST':
                $parameters = $database->query($query, array('bucket' => $bucket, 'docNme' => 'post_clients_register_required'))->docValues;

                if($request->getParameters() != $parameters) {
                    $request->setResponseCode(400);
                    $request->setValid(false);

                    return;
                }
            break;
        }
    }
    private function protocol($protocol)
    {
        return in_array($protocol, self::VALID_PROTOCOLS);
    }
    private function method($method)
    {
        return in_array($method, self::VALID_METHODS);
    }

    private function sender($sender)
    {
        return in_array($sender, self::VALID_SENDERS);
    }
    private function body($body)
    {
        if (!$this->isValidBody($body))
        {
            $request->setResponseCode(422);
            $request->setValid(false);
            return $request;
        }
        $request->setValid(true);
        $request->setResponseCode(200);
        return $request;
    }
}
