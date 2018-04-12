<?php
require_once("common.php");

// This is quick-and-dirty; I'd use something like PHPUnit in production and check the exact errors.

$test_files = array(
	'bad.csv' => 1,
	'bad_group.csv' => 7,
	'bad_person.csv' => 7,
	'empty.csv' => 1,
	'good_group.csv' => 0,
	'good_person.csv' => 0);

foreach($test_files as $file => $expected_errors)
{
	$contents = file("test_uploads/$file");
	$csv = array_map('str_getcsv', $contents);
	$count_errors = count(ProcessCSVUpload($csv));
	echo $count_errors == $expected_errors ? 
		"$file passed<br/>" : 
		"INCORRECT NUMBER OF ERRORS FOR $file: found $count_errors, expected $expected_errors<br/>";
}