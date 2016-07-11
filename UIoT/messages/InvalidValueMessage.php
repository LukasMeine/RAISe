<?php

namespace UIoT\messages;

use UIoT\model\RaiseMessage;
use UIoT\model\RaiseMessageContent;

/**
 * Class InvalidValueMessage
 * @package UIoT\messages
 */
final class InvalidValueMessage extends RaiseMessage
{
    /**
     * InvalidValueMessage constructor.
     */
    public function __construct()
    {
        $message = new RaiseMessageContent;
        $message->addContent('message', 'Data has Invalid Value');

        parent::__construct(5, $message);
    }
}