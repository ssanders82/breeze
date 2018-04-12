<?php
require_once('common.php');

$groups = Group::SelectAll();
foreach($groups as $group)
{
	$group->_people = Person::SelectAllActive($group->group_id);
}
$x = new stdClass();
$x->data = $groups;
echo json_encode($x);