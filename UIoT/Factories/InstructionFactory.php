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

namespace UIoT\Factories;

use NilPortugues\Sql\QueryBuilder\Builder\MySqlBuilder;
use NilPortugues\Sql\QueryBuilder\Manipulation\AbstractBaseQuery;
use NilPortugues\Sql\QueryBuilder\Manipulation\Insert;
use NilPortugues\Sql\QueryBuilder\Manipulation\Select;
use NilPortugues\Sql\QueryBuilder\Manipulation\Update;
use NilPortugues\Sql\QueryBuilder\Syntax\Where;
use Symfony\Component\HttpFoundation\ParameterBag;
use UIoT\Interfaces\PropertyInterface;
use UIoT\Interfaces\ResourceInterface;
use UIoT\Managers\RaiseManager;
use UIoT\Models\FactoryModel;

/**
 * Class InstructionFactory
 * @package UIoT\Factories
 */
class InstructionFactory extends FactoryModel
{
    /**
     * MySQL Instruction Builder Instance
     *
     * The RAISe uses MySQL Query Builder to
     * perform higher dynamic QUERY operations
     *
     * @var MySqlBuilder
     */
    private $builder;

    /**
     * The Query Type is matched
     * by the HTTP Request Method
     *
     * @var string
     */
    private $type;

    /**
     * This property contains the HTTP
     * Request Query String following the
     * IETF RFC 3986
     *
     * @see https://tools.ietf.org/html/rfc3986
     *
     * @var ParameterBag
     */
    private $query;

    /**
     * The Resource Model of the Requested RAISe Resource
     *
     * @var ResourceInterface|ResourceInterface[]
     */
    private $resource;

    /**
     * Contains the Manipulations of the SQL Builder
     *
     * @var Select|Insert|Update
     */
    private $instruction;

    /**
     * Normally a RAISE Factory does'nt have any parameters
     * The Factory normally will do his business logic in a black box.
     * In other words, the Factory will request the necessary data to work
     * through the RAISE Managers
     */
    public function __construct()
    {
        $this->builder = new MySqlBuilder;
    }

    /**
     * Get the SQL Instruction String
     * to be used in Prepared Statement
     *
     * @return string
     */
    public function getInstruction()
    {
        return $this->builder->write($this->execute());
    }

    /**
     * Executes the Instruction if does'nt has been executed.
     * And Return it.
     *
     * @return Insert|Select|Update
     */
    private function execute()
    {
        if ($this->instruction === null) {
            $this->type = RaiseManager::getHandler('request')->getRequest()->getMethod();
            $this->query = RaiseManager::getHandler('request')->getRequest()->query;
            $this->resource = RaiseManager::getHandler('request')->getResource();
            $this->instruction = $this->setCriteria();
        }

        return $this->instruction;
    }

    /**
     * Set the Criteria of the SQL Instruction
     *
     * @return Select|Insert|Update SQL Instruction
     */
    private function setCriteria()
    {
        $columns = $this->setColumns();

        switch ($this->type) {
            case 'GET':
                $this->getCriteria($columns->where(), $this->getValues(['token' => '']));
                return $columns;
            case 'POST':
                return $this->setColumns();
            default:
                $this->getCriteria($columns->where(), ['ID' => $this->query->get('id')]);
                return $columns;
        }
    }

    /**
     * Set the Columns or the Values of the SQL Instruction
     *
     * @return Select|Insert|Update|AbstractBaseQuery SQL Instruction
     */
    private function setColumns()
    {
        $type = $this->setType();

        switch ($this->type) {
            case 'GET':
                $type->setColumns($this->getProperties());
                return $type;
            case 'DELETE':
                $type->setValues(['DELETED' => 1]);
                return $type;
            default:
                $type->setValues($this->getValues(['token' => '', 'id' => '']));
                return $type;
        }
    }

    /**
     * Set SQL Instruction Statement Type
     *
     * @return Select|Insert|Update|AbstractBaseQuery
     */
    private function setType()
    {
        switch ($this->type) {
            case 'GET':
                return $this->builder->select()->setTable($this->resource->getInternalName());
            case 'POST':
                return $this->builder->insert()->setTable($this->resource->getInternalName());
            default:
                return $this->builder->update()->setTable($this->resource->getInternalName());
        }
    }

    /**
     * Get All Resource Properties for the SQL Instruction
     *
     * @return array
     */
    private function getProperties()
    {
        return array_map(function ($property) {
            /** @var $property PropertyInterface */
            return $property->getInternalName();
        }, $this->resource->getProperties()->getAll());
    }

    /**
     * Get Values by the Query String with the option of
     * Remove some of them
     *
     * @param array $valuesToRemove
     * @return array Values to Get
     */
    private function getValues(array $valuesToRemove = array())
    {
        $values = array();

        foreach (array_diff_key($this->query->all(), $valuesToRemove) as $property => $value) {
            $values[$this->resource->getProperties()->get($property)->getInternalName()] = $value;
        }

        return $values;
    }

    /**
     * Applies a Criteria in the SQL Instruction
     *
     * @param Where $where Where Operator
     * @param array $criteria SQL Criteria
     * @return Where Final Operator
     */
    private function getCriteria(Where $where, array $criteria)
    {
        foreach ($criteria as $property => $value) {
            $where->equals($property, $value);
        }

        $where->equals('DELETED', 0);

        return $where;
    }

    /**
     * Get SQL Instruction Prepared Statements
     * Arguments to be used in Prepared Statement
     *
     * @return array
     */
    public function getStatement()
    {
        $this->execute();

        return $this->builder->getValues();
    }
}
