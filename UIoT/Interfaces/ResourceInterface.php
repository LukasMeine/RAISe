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

namespace UIoT\Interfaces;

use UIoT\Factories\PropertyFactory;

/**
 * Interface ResourceInterface
 *
 * This Interface contains an UIoT Resource Interface.
 * An UIoT Resource it's a specific set of data with a fully own type.
 * @example <Devices>,<Actions>,<Services>
 *
 * @package UIoT\Interfaces
 */
interface ResourceInterface
{
    /**
     * Return the Resource Unique Identifier <ID>
     *
     * The identifier is stored in `META_RESOURCES` Database Table
     *
     * @return int
     */
    public function getId();

    /**
     * Return the Resource Shorthand tag identifier <RSRC_ACRONYM>
     *
     * The Resource Acronym is the short tag identifier for a Resource,
     * and is not important for the `client`. Only useful for internal storage.
     *
     * Generally the Acronym is a four character digit string, but can variate from 3 to 5 digits.
     * Only characters are allowed in a Acronym tag. Not numbers allowed.
     *
     * @return string
     */
    public function getAcronym();

    /**
     * Return the Resource Internal Name <RSRC_NAME>
     *
     * This name is only used for internal SQL Operations, and is not available
     * or readable to the `client`
     *
     * @return string
     */
    public function getInternalName();

    /**
     * Return the Resource Friendly Name <RSRC_FRIENDLY_NAME>
     *
     * The Resource Friendly Name is the public identification that a `client` can get
     * from a specific Resource. RAISE will only accept from a `client` a Resource Friendly Name
     * for Resource Identification.
     *
     * Also only the Resource Friendly Name is answered for the `client`, since the Internal Name <RSRC_NAME>
     * isn't a public identification.
     *
     * @return string
     */
    public function getFriendlyName();

    /**
     * Return the Properties from the Resource
     *
     * The Properties are stored inside a Resource and only Populated
     * after the Instantiation of the Resource Model
     *
     * @return PropertyFactory
     */
    public function getProperties();
}
