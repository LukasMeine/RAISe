<?php
/**
 * class SQLFilter
 * class that represents filters on SQL instructions
 * example's: name = 'teste', age > 10, salary != 5000
 */

final class SQLFilter
{
    private $column_name;
    private $operator;
    private $value;

    /**
     * method __construct
     * instantiates a new SQLFilter
     * @param $column_name : string
     * @param $operator    : string -> (=, >, <, >=, <=, <>, !=, !<, !>)
     * @param $value       : mixed -> can be (boolean, float, int, string, array or null)
     *
     */

    public function __construct($column_name, $operator, $value)
    {
        self::set_column_name($column_name);
        self::set_operator($operator);
        self::set_value($value);
    }

    /**
     * method set_column_name
     * sets $column_name value
     * @param $column_name : string
     */

    private function set_column_name($column_name)
    {
        $this->column_name = $column_name;
    }

    /**
     * method set_operator
     * sets $operator value
     * @param $operator : string

     */

    private function set_operator($operator)
    {
        $this->operator = $operator;
    }

     /**
     * method set_value
     * sets $value atributte value
     * @param $value : mixed
     */

    private function set_value($value)
    {
        $this->value = $value;
    }

   public function to_sql()
   {
       return "{$this->column_name} {$this->operator} {$this->value}";
   }

    /**
     * method value_to_string
     * transforms $value to sql string
     * @param $value : mixed -> (boolean, float, int, string, array or null)
     *
     * @return $result :  string|float|int
     */

     private function value_to_string($value)
     {
        if(is_array($value))
        {
            foreach($value as $v)
            {
                if(is_int($value) || is_float($value))
                {
                    $foo[] = "'$v'";
                }
		else if(is_string($v))
		{
 	          $foo[] = $v;
		}
            }
	    return '('.implode(',', $foo).')';
        }
        else if(is_string($value))
        {
          return "'$value'";
        }
        else if(is_null($value))
        {
          return 'NULL';
        }
        else if(is_bool($value))
        {
          return $value ? 'TRUE' : 'FALSE';
        }
        else
        {
          return $value;
        }
   }

}

