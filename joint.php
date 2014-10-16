<?php 
$teachers = json_decode($_REQUEST["teacher"]);	

include "connect.php";

$lessons=[];
$color = 0;

foreach ($teachers as $teacher) {	
	foreach (getTeacherLessons($con, $teacher) as $lesson) {
		$t = $lesson->teacher;
		$r = $lesson->room;
		$data = $lesson;
		$day = $lesson->day;
		$time = $lesson->lesson_time;
		$week = $lesson->week;
		if (!(array_key_exists($day, $lessons) &&
			array_key_exists($time, $lessons[$day]) &&
				array_key_exists($week, $lessons[$day][$time]) &&
					in_array($data, $lessons[$day][$time][$week]))) {
						$lessons[$day][$time][$week][]=$data;
		}
	}	
}

mysqli_close($con);

print json_encode($lessons);

function getTeacherLessons($con, $teacher){
	$sth = mysqli_query($con,"SELECT * from timetable where teacher=$teacher");	
	if (!$sth)
		return;
	$lessons = [];
	while($r = mysqli_fetch_object($sth)) {		
		$lessons[] = $r;
	}
	return $lessons;
}

?>