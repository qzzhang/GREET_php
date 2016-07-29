<?php

class TableSorter {
  protected $column;
  protected $way;
  protected $type;
  
  function __construct($column,$way,$type) {
    $this->column = $column;
	$this->way = $way;
	$this->type = $type;
  }
  function sort($table) 
  {
	if($this->way == 'up')
	{
		if($this->type=='int')
			usort($table, array($this, 'compare_int_up'));
		else if($this->type=='string')
			usort($table, array($this,'compare_string_up'));
	}
	else if($this->way == 'down')
	{
		if($this->type=='int')
			usort($table, array($this, 'compare_int_down'));
		else if($this->type=='string')
			usort($table, array($this,'compare_string_down'));
	}
	
	return $table;
  }
  function compare_int_up($a, $b) {
    if ($a[$this->column] == $b[$this->column]) {
      return 0;
    }
    return ($a[$this->column] < $b[$this->column]) ? -1 : 1;
  }
  
  function compare_int_down($a, $b) {
    if ($a[$this->column] == $b[$this->column]) {
      return 0;
    }
    return ($a[$this->column] > $b[$this->column]) ? -1 : 1;
  }
  
  function compare_string_up($a, $b) {
	
    return strnatcmp($a[$this->column], $b[$this->column]);
  }
  
  function compare_string_down($a, $b) {
    return strnatcmp($b[$this->column], $a[$this->column]);
  }
  
} 
?>