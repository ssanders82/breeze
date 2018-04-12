<?php
class Group
{
	var $group_id;
	var $group_name;
	var $_people = array();
	
	function __construct($db_obj = null)
	{
		if (is_object($db_obj)) $this->Populate($db_obj);
	}
	
	function Save()
    {
        $sql = "INSERT INTO tbl_group (group_id, group_name) VALUES (:group_id, :group_name)
        	ON DUPLICATE KEY UPDATE group_name=:group_name";
        $params = array(':group_id' => $this->group_id, ':group_name' => $this->group_name);
        
        DatabaseAccess::Execute($sql, $params);
    }
    
    private function Populate($row)
    {
        $this->group_id	= $row->group_id;
        $this->group_name = $row->group_name;
    }
    
    static function SelectAll()
    {
    	$sql = "SELECT * FROM tbl_group ORDER BY group_name";
        $results = DatabaseAccess::Select($sql);
        $groups = array();
        foreach ($results as $row)
        {
        	$groups[] = new Group($row);
        }
    	return $groups;
    }
    
    static function ValidateCSVRow($row, $row_number)
    {
    	if (count($row) != 2) return array("Invalid CSV row $row_number");
    	$errors = array();
    	if (!IsPositiveInteger($row[0])) $errors[] = "Invalid group_id '{$row[0]}' on row $row_number";
    	if (strlen($row[1]) > 255) $errors[] = "Too long group_name '{$row[1]}' on row $row_number";
    	return $errors;
    }
    
    static function SaveCSVRow($row)
    {
    	$group = new Group();
    	$group->group_id = $row[0];
    	$group->group_name = $row[1];
    	$group->Save();
    }
}