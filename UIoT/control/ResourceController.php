<?php

namespace UIoT\control;

use UIoT\database\DatabaseConnector;
use UIoT\database\DatabaseExecuter;
use UIoT\model\Request;
use UIoT\exceptions\InvalidColumnNameException;
use UIoT\exceptions\InvalidMethodException;
use UIoT\sql\SQLDelete;
use UIoT\sql\SQLInsert;
use UIoT\sql\SQLSelect;
use UIoT\sql\SQLUpdate;
use UIoT\sql\SQLCriteria;
use UIoT\sql\SQLFilter;
use UIoT\sql\SQL;

/**
 * Class ResourceController
 * @package UIoT\control
 */
class ResourceController
{
    /**
     * @var DatabaseConnector
     */
    var $db_connector;
    /**
     * @var DatabaseExecuter
     */
    var $db_executer;

    /**
     * ResourceController constructor.
     */
    public function __construct()
    {
        self::create_db_executer();
        self::create_db_connector();
    }

    /**
     *
     */
    private function create_db_executer()
    {
        $this->db_executer = new DatabaseExecuter();
    }

    /**
     *
     */
    private function create_db_connector()
    {
        $this->db_connector = new DatabaseConnector();
    }

    /**
     * @param $request
     * @return mixed
     */
    public function execute_request($request)
    {
        $resource = $this->create_resource($request);
        return $this->db_executer->execute($resource->get_instruction(), $this->db_connector->get_PDO_object());
    }

    /**
     * @return mixed
     */
    private function get_connection()
    {
        return $this->db_connector->get_PDO_object();
    }

    /**
     * @param $request
     * @return SQLDelete|SQLInsert|SQLSelect|SQLUpdate
     * @throws InvalidColumnNameException
     * @throws InvalidMethodException
     */
    private function create_resource(Request $request)
    {
       $id          =  $this->get_resource_id($request->get_resource());
       $table_name  =  $this->get_resource_table_name($request->get_resource());
       $instruction =  $this->get_resource_instruction($request->get_method());
       $columns     =  $this->get_column_names($id);

       if(!empty($request->get_parameters()))
           $criteria = $this->get_criteria($id, $request->get_parameters());
       else
           $criteria = new SQLCriteria();

       $instruction->set_criteria($criteria);
       $instruction->set_entity($table_name);
       $instruction->add_columns($columns);


        return $instruction;
    }

    /**
     * @param $resource
     * @return mixed
     * @throws \UIoT\exceptions\InvalidSqlOperatorException
     * @throws \UIoT\exceptions\NotSqlFilterException
     */
    private function get_resource_table_name($resource)
    {
        $instruction = new SQLSelect();
        $criteria = new SQLCriteria();
        $criteria->add_filter(new SQLFilter('RSRC_FRIENDLY_NAME', SQL::EQUALS_OP(), $resource), SQL::AND_OP());
        $instruction->set_criteria($criteria);
        $instruction->add_column('RSRC_NAME');
        $instruction->set_entity('META_RESOURCES');

        return $this->db_executer->execute($instruction->get_instruction(), $this->get_connection())[0]['RSRC_NAME'];
    }

    /**
     * @param $method
     * @return SQLDelete|SQLInsert|SQLSelect|SQLUpdate
     * @throws InvalidMethodException
     */
    private function get_resource_instruction($method)
    {
        switch($method)
        {
            case 'GET':
                return new SQLSelect();
            case 'POST':
                return new SQLInsert();
            case 'PUT':
                return new SQLUpdate();
            case 'DELETE':
                return new SQLDelete();
            default:
                throw new InvalidMethodException("Http method not supported");
        }
    }

    /**
     * @param $id
     * @return array
     * @throws \UIoT\exceptions\InvalidSqlOperatorException
     * @throws \UIoT\exceptions\NotSqlFilterException
     */
    private function get_column_names($id)
    {
        $instruction = new SQLSelect();
        $criteria = new SQLCriteria();
        $criteria->add_filter(new SQLFilter('RSRC_ID', SQL::EQUALS_OP(), $id), SQL::AND_OP());
        $instruction->set_criteria($criteria);
        $instruction->add_column('PROP_NAME');
        $instruction->set_entity('META_PROPERTIES');

        return $this->extract_column_names($this->db_executer->execute($instruction->get_instruction(), $this->get_connection()));
    }

    /**
     * @param $resource_name
     * @return mixed
     * @throws \UIoT\exceptions\InvalidSqlOperatorException
     * @throws \UIoT\exceptions\NotSqlFilterException
     */
    private function get_resource_id($resource_name)
    {
        $instruction = new SQLSelect();
        $criteria = new SQLCriteria();
        $criteria->add_filter(new SQLFilter('RSRC_FRIENDLY_NAME', SQL::EQUALS_OP(), $resource_name), SQL::AND_OP());
        $instruction->set_criteria($criteria);
        $instruction->add_column('ID');
        $instruction->set_entity('META_RESOURCES');

        return $this->db_executer->execute($instruction->get_instruction(), $this->get_connection())[0]['ID'];
    }

    /**
     * @param $id
     * @param $friendly_name
     * @return mixed
     * @throws \UIoT\exceptions\InvalidSqlOperatorException
     * @throws \UIoT\exceptions\NotSqlFilterException
     */
    private function get_column_name($id, $friendly_name)
    {

        $instruction = new SQLSelect();
        $criteria = new SQLCriteria();
        $criteria->add_filter(new SQLFilter('PROP_FRIENDLY_NAME', SQL::EQUALS_OP(), $friendly_name), SQL::AND_OP());
        $criteria->add_filter(new SQLFilter('RSRC_ID', SQL::EQUALS_OP(), $id), SQL::AND_OP());
        $instruction->set_criteria($criteria);
        $instruction->add_column('PROP_NAME');
        $instruction->set_entity('META_PROPERTIES');

        return $this->db_executer->execute($instruction->get_instruction(), $this->get_connection());
    }

    /**
     * @param $id
     * @param $parameters
     * @return SQLCriteria
     * @throws InvalidColumnNameException
     * @throws \UIoT\exceptions\InvalidSqlOperatorException
     * @throws \UIoT\exceptions\NotSqlFilterException
     */
    private function get_criteria($id, $parameters)
    {
        $criteria = new SQLCriteria();
        foreach($parameters as $key => $value)
        {
            $column = $this->get_column_name($id, $key);
            if(is_null($column))
                throw new InvalidColumnNameException();

            $filter = new SQLFilter($column[0]['PROP_NAME'], SQL::EQUALS_OP(), $value);
            $criteria->add_filter($filter, SQL::AND_OP());
        }

        return $criteria;
    }

    /**
     * @param $raw_columns_array
     * @return array
     */
    private function extract_column_names($raw_columns_array)
    {
        $columns = array();
        foreach($raw_columns_array as $key => $column_name_array)
        {
            foreach($column_name_array as $column_name)
                $columns[] = $column_name;
        }
        return $columns;
    }
}