<?php

define("MYSQL_LOCATION", "localhost");
define("MYSQL_USER",     "breeze");
define("MYSQL_PASSWORD", "breeze");
define("DATABASE_NAME",  "breeze");

require_once('group.php');
require_once('person.php');
require_once('database_access.php');

function IsPositiveInteger($val)
{
	return filter_var($val, FILTER_VALIDATE_INT, array('options' => array( 'min_range' => 0)));
}

function ProcessCSVUpload($csv)
{
	if (count($csv) == 0) return array("Empty CSV file detected");
	if (count($csv) == 1) return array("No data rows detected");
	$header = array_map('strtolower', $csv[0]);
	if ($header == array("group_id", "group_name"))
	{
		return ProcessGroupsCSVUpload($csv);
	}
	else if ($header == array("person_id", "first_name", "last_name", "email_address", "group_id", "state"))
	{
		return ProcessPeopleCSVUpload($csv);
	}
	else
	{
		return array("Invalid CSV header row detected");
	}
}

function ProcessGroupsCSVUpload($csv)
{
	$errors = array();
	for ($i = 1; $i < count($csv); $i++)
	{
		$errors = array_merge($errors, Group::ValidateCSVRow($csv[$i], $i));
	}
	if (count($errors) > 0) return $errors;
	for ($i = 1; $i < count($csv); $i++)
	{
		Group::SaveCSVRow($csv[$i]);
	}
	return array();
}

function ProcessPeopleCSVUpload($csv)
{
	$groups = Group::SelectAll();
	$group_ids = array();
	foreach($groups as $group) $group_ids[] = $group->group_id;
	
	$errors = array();
	for ($i = 1; $i < count($csv); $i++)
	{
		$errors = array_merge($errors, Person::ValidateCSVRow($csv[$i], $i, $group_ids));
	}
	if (count($errors) > 0) return $errors;
	for ($i = 1; $i < count($csv); $i++)
	{
		Person::SaveCSVRow($csv[$i]);
	}
	return array();
}