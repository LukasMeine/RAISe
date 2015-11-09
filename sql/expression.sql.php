<?php

/**
 * class SQLExpression
 * class that represents a collection of SQLFilters related by logic operands
 * example: name = 'teste' AND age > 10 OR salary != 5000
 */

final class SQLExpression
{
    private $filters;

    public function __construct($filter)
    {
	$this->filters[] = $filter;
    }

    public function add_filter($filter, $logic_operation)
    {
         $this->filters[] = $logic_operation;
         $this->filters[] = $filter;
    }
    
    public function to_sql()
    {
	//TODO
    }	
			
}
