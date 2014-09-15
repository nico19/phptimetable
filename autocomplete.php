<?php
include "connect.php";
$teacher = $_REQUEST["teacher"];
if (!$teacher) {
	$teacher = "";
}
$sth = mysqli_query($con,"SELECT distinct teacher from timetable where teacher like '%$teacher%'");	
if (!$sth)
	return;
$teachers = [];
while($r = mysqli_fetch_object($sth)) {		
	$teachers[] = $r->teacher;
}
print json_encode($teachers);	
?>