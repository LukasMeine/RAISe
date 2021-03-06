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

namespace UIoT\Managers;

use UIoT\Factories\SettingsFactory;
use UIoT\Interfaces\SettingsInterface;
use UIoT\Mappers\Json;

/**
 * Class SettingsManager
 * @package UIoT\Managers
 */
class SettingsManager
{
    /**
     * Settings Factory Instance
     *
     * @var SettingsFactory
     */
    private $settingFactory;

    /**
     * Get Settings Manager Instance
     *
     * @return SettingsManager
     */
    public static function getInstance()
    {
        static $instance = null;

        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Add a Settings Block by Json Mapper
     *
     * @param SettingsInterface $itemInstance
     * @param array $itemProperties
     */
    public function addItem($itemInstance, array $itemProperties)
    {
        $this->getFactory()->add(Json::getInstance()->convert((object)$itemProperties, $itemInstance));
    }

    /**
     * Get Settings Factory Instance
     *
     * @return SettingsFactory
     */
    public function getFactory()
    {
        if (null === $this->settingFactory) {
            $this->settingFactory = new SettingsFactory;
        }

        return $this->settingFactory;
    }

    /**
     * Get a Settings Model Block
     *
     * @param string $itemIdentifier
     * @return SettingsInterface|SettingsInterface[]
     */
    public function getItem($itemIdentifier)
    {
        return $this->getFactory()->get($itemIdentifier);
    }
}
