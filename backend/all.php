<?php
include "connect.php";

$sth = mysqli_query($con,"SELECT * from ".$_REQUEST["table"]);	
if (!$sth)
	return;
$teachers = [];
while($r = mysqli_fetch_object($sth)) {		
	$teachers[] = $r;
}
print json_encode($teachers);	
?>