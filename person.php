<?php
class Person
{
	var $person_id;
	var $first_name;
	var $last_name;
	var $email_address;
	var $group_id;
	var $state;
	
	function __construct($db_obj = null)
	{
		if (is_object($db_obj)) $this->Populate($db_obj);
	}
	
	function Save()
    {
        $sql = "INSERT INTO tbl_person (person_id,first_name, last_name, email_address,group_id,state) 
        	VALUES (:person_id,:first_name, :last_name, :email_address, :group_id, :state)
        	ON DUPLICATE KEY UPDATE first_name=:first_name,last_name=:last_name,
        	email_address=:email_address,group_id=:group_id,state=:state";
        
        $params = array(
        	':person_id' => $this->person_id,
        	':first_name' => $this->first_name,
        	':last_name' => $this->last_name,
        	':email_address' => $this->email_address,
        	':group_id' => $this->group_id,
        	':state' => $this->state,
        );
        
        DatabaseAccess::Execute($sql, $params);
    }
    
    private function Populate($row)
    {
        $this->person_id = $row->person_id;
        $this->first_name = $row->first_name;
        $this->last_name = $row->last_name;
        $this->email_address = $row->email_address;
        $this->group_id = $row->group_id;
        $this->state = $row->state;
    }
    
    static function SelectAllActive($group_id = 0)
    {
    	$sql = "SELECT * FROM tbl_person WHERE :group_id in (0,group_id) AND State='active' ORDER BY last_name, first_name";
    	$results = DatabaseAccess::Select($sql, array(':group_id' => $group_id) );
    	$people = array();
        foreach ($results as $row)
        {
        	$people[] = new Person($row);
        }
    	return $people;
    }
    
    static function ValidateCSVRow($row, $row_number, $group_ids)
    {
    	if (count($row) != 6) return array("Invalid CSV row $row_number");
    	$errors = array();
    	if (!IsPositiveInteger($row[0])) $errors[]= "Invalid person_id '{$row[0]}' on row $row_number";
    	if (strlen($row[1]) > 255) $errors[]= "Too long first_name '{$row[1]}' on row $row_number";
    	if (strlen($row[2]) > 255) $errors[]= "Too long last_name '{$row[2]}' on row $row_number";
    	if (!filter_var($row[3], FILTER_VALIDATE_EMAIL)) $errors[]= "Invalid email_address '{$row[3]}' on row $row_number";
    	if (!in_array($row[4], $group_ids)) $errors[]= "Invalid group_id '{$row[4]}' on row $row_number";
    	if (!in_array($row[5], array('active','archived') )) $errors[]= "Invalid state '{$row[5]}' on row $row_number";
    	return $errors;
    }
    
    static function SaveCSVRow($row)
    {
    	$person = new Person();
    	$person->person_id = $row[0];
    	$person->first_name = $row[1];
    	$person->last_name = $row[2];
    	$person->email_address = $row[3];
    	$person->group_id = $row[4];
    	$person->state = $row[5];
    	$person->Save();
    }
}