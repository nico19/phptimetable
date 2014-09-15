<?php 
$colors=[
"Aqua",
"Aquamarine",
"BlueViolet",
"Brown",
"BurlyWood",
"CadetBlue",
"Chartreuse",
"Coral",
"CornflowerBlue",
"Crimson",
"Cyan",
"DarkCyan",
"DarkGoldenRod",
"DarkGray",
"DarkKhaki",
"DarkOrange",
"DeepPink",
"GreenYellow",
"LightGreen",
"Sienna",
"Red",
"Tomato"
];

$teachers = json_decode($_REQUEST["teacher"]);	



include "connect.php";

$lessons=[];
$color = 0;

foreach ($teachers as $teacher) {
	$colorString = $colors[$color];	
	foreach (getTeacherLessons($con, $teacher) as $lesson) {
		$t = $lesson->teacher;
		$r = $lesson->room;
		$txt = "<p style='background-color:$colorString'>$t в а.$r</p>";
		$day = $lesson->day;
		$time = $lesson->lesson_time;
		$week = $lesson->week;
		if (!(array_key_exists($day, $lessons) &&
			array_key_exists($time, $lessons[$day]) &&
				array_key_exists($week, $lessons[$day][$time]) &&
					in_array($txt, $lessons[$day][$time][$week]))) {
						$lessons[$day][$time][$week][]=$txt;
		}
	}	
	$color++;
}

mysqli_close($con);

print json_encode($lessons);

function getTeacherLessons($con, $teacher){
	$sth = mysqli_query($con,"SELECT * from timetable where teacher like '%$teacher%'");	
	if (!$sth)
		return;
	$lessons = [];
	while($r = mysqli_fetch_object($sth)) {		
		$lessons[] = $r;
	}
	return $lessons;
}

?>