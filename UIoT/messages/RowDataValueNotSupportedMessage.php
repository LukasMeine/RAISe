<?php

namespace UIoT\messages;

use UIoT\model\RaiseMessage;
use UIoT\model\RaiseMessageContent;

/**
 * Class RowDataValueNotSupportedMessage
 * @package UIoT\messages
 */
final class RowDataValueNotSupportedMessage extends RaiseMessage
{
    /**
     * RowDataValueNotSupportedMessage constructor.
     */
    public function __construct()
    {
        $message = new RaiseMessageContent;
        $message->addContent('message', 'Invalid Request Data');

        parent::__construct(2, $message);
    }
}