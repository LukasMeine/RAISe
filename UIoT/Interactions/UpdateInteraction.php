<?php

/**
 * UIoT Service Layer
 * @version beta
 *                          88
 *                          ""              ,d
 *                                          88
 *              88       88 88  ,adPPYba, MM88MMM
 *              88       88 88 a8"     "8a  88
 *              88       88 88 8b       d8  88
 *              "8a,   ,a88 88 "8a,   ,a8"  88,
 *               `"YbbdP'Y8 88  `"YbbdP"'   "Y888
 *
 * @author Universal Internet of Things
 * @license MIT <https://opensource.org/licenses/MIT>
 * @copyright University of Brasília
 */

namespace UIoT\Interactions;

use UIoT\Managers\InteractionManager;
use UIoT\Models\InteractionModel;

/**
 * Class UpdateInteraction
 * @package UIoT\Interactions
 */
class UpdateInteraction extends InteractionModel
{
    /**
     * Method that executes the Business Logic
     * and does all Controlling operations.
     *
     * @note Interaction is similar as a Controller
     *
     * @return void
     */
    public function execute()
    {
        if ($this->checkResource('devices')) {
            InteractionManager::getInstance()->execute('UpdateTokenInteraction');
        } elseif ($this->checkToken() !== 1) {
            $this->setMessage('InvalidToken');
        } else {
            $this->getInstruction()->execute();
            $this->setMessage('ResourceItemUpdated');
        }
    }

    /**
     * Method that prepares the Business Logic
     * checking if all checks passes
     *
     * Return if passed or not.
     *
     * @return bool
     */
    public function prepare()
    {
        if (($result = $this->checkArguments(['id'], true)) !== true && !$this->checkResource('devices')) {
            $this->setMessage('RequiredArgument', ['argument' => 'id']);
        }

        return $result === true || $this->checkResource('devices');
    }
}