<?php

namespace UIoT\callbacks;

use UIoT\managers\RequestManager;
use UIoT\messages\InvalidTokenMessage;
use UIoT\model\CallBack;
use UIoT\model\UIoTRequest;
use UIoT\util\MessageHandler;

/**
 * Class ExecutePutCallBack
 * @package UIoT\callbacks
 */
class ExecutePutCallBack extends CallBack
{
    /**
     * Get a CallBack result
     *
     * @param UIoTRequest $request
     * @return mixed
     */
    public static function getCallBack(UIoTRequest $request)
    {
        if (RequestManager::getTokenManager()->validateCode($request->query->get('token'))) {
            return RequestManager::getRequest()->executeRequest();
        }

        return MessageHandler::getInstance()->getResult(new InvalidTokenMessage);
    }
}