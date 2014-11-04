<?php


$file = "roomBussiness.json";

$fp = fopen($file.'.lock', "w");
if (flock($fp, LOCK_EX)) {  // acquire an exclusive lock   
	if (!file_exists($file)) {
		file_put_contents($file, getRoomBusiness());
	}
 
    flock($fp, LOCK_UN);    // release the lock
} else {
    echo "Couldn't get the lock!";
}
fclose($fp);

print file_get_contents($file);



function getRoomBusiness(){
	include "connect.php";

	$sth = mysqli_query($con,"SELECT distinct room from timetable order by room");	
	if (!$sth)
		return;
	$rooms = [];
	while($r = mysqli_fetch_object($sth)) {		
		$rooms[] = $r->room;
	}

	$business=[];

	foreach ($rooms as $room){		
		$records = getTimesForRoom($con, $room);	
		$bussinessForRoom = [];
		foreach($records as $record){
			$bussinessForRoom[]=$record;
		}
		$business[$room]=$bussinessForRoom;
	}

	return json_encode($business);	
}

function getTimesForRoom($con, $room){
	$sth = mysqli_query($con,"SELECT day,lesson_time,week from timetable where room='$room' order by day,lesson_time,week");	
	if (!$sth)
		return;
	$records = [];
	while($r = mysqli_fetch_object($sth)) {		
		$records[$r->day][$r->lesson_time][$r->week]=true;
	}
	return $records;
}
?>