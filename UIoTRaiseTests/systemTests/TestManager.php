<?php

namespace UIoTRaiseTestes\systemTests;

/**
 * Class TestManager
 * @package UIoTRaiseTestes\systemTests
 */
class TestManager
{
    const RAISE_URI = "http://raise.uiot.org";

    /**
     * @var array requests
     */
    private $requests;

    /**
     * TestManager constructor.
     */
    public function __construct()
    {
        $testResults = "Test results:";

        $this->requests = $this->populateRequests();
        
        foreach($this->requests as $request) {
            $testResults .= "\n".$request->getName().": ".$this->testRequests($request).".";
        }
        
        echo $testResults;
    }

    /**
     * @param RequestTestCase $requestTestCase
     * @return string
     */
    private function testRequests(RequestTestCase $requestTestCase)
    {
        $response = $requestTestCase->sendRequest();

        if(strcmp($response, $requestTestCase->getExpected()) === 0) {
            return "pass";
        } else {
            return "fail";
        }
    }

    /**
     * Constructs requests based on
     * @see https://github.com/UIoT/uiot_academics/blob/master/docs/documentation/DeviceRequests.pdf
     * @return array
     */
    private function populateRequests()
    {
        $requestArray = array(
            new RequestTestCase("authentication document","POST", $this::RAISE_URI."/devices",
                "{
                    id_chipset:
                    id_processor:
                    hd_serial:
                    host_name:
                    mac:
                    driver:
                }",
                "{
                    code:
                    id_token:
                }"),
            new RequestTestCase("register document","POST", $this::RAISE_URI."/services",
                "{
                    id_token:
                    mac:
                    services:
                    [
                        {
                            name:
                            inf_type:
                            unit:
                            description:
                        }
                        ...
                    ]
                }",
                "{
                    code:
                    services:
                    [
                        {
                        name:
                        id_serv:
                        timing:
                        }
                    ]
                }"),
            new RequestTestCase("device services update","PUT", $this::RAISE_URI."/services",
                "{
                    id_token:
                    mac:
                    services:
                    [
                        {
                        id_serv:
                        value:
                        }
                        ...
                    ]
                }",
                "{
                    code:
                }")
        );

        return $requestArray;
    }
}
