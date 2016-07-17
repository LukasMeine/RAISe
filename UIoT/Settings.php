<?php

/**
 * UIoT Service Layer
 * @version alpha
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

use UIoT\Managers\SettingsManager as S;
use UIoT\Models\Settings\DatabaseSettingsModel;
use UIoT\Models\Settings\SecuritySettingsModel;

/* RAISE Security Settings Block */
S::getInstance()->addItem(new SecuritySettingsModel, [
    'tokenExpirationTime' => 3600,
    'tokenUpdateTime' => 3600
]);

/* RAISE Database Settings Block */
S::getInstance()->addItem(new DatabaseSettingsModel, [
    'hostName' => 'localhost',
    'hostPort' => 3306,
    'connUser' => 'root',
    'connPass' => '',
    'connDataBase' => 'UIOT'
]);

