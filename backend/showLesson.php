<?php 
include "connect.php";
$room = $_REQUEST["room"];
$day = $_REQUEST["day"]+1;
$time = $_REQUEST["time"];
$week = $_REQUEST["week"];

$stmt = "SELECT timetable.day, timetable.lesson_time, timetable.week, timetable.room, teacher.value as teacherName, subject.value as subjectTitle, st_group.value as groupTitle, faculty.value as facultyTitle FROM teacher, subject, st_group, faculty, timetable WHERE timetable.room=$room AND timetable.day=$day AND timetable.lesson_time='$time' AND timetable.week=$week AND teacher.id=timetable.teacher AND subject.id=timetable.subject AND st_group.id=timetable.st_group AND faculty.id=timetable.faculty";

//echo $stmt;

$sth = mysqli_query($con,$stmt) or die(mysql_error());	

while($r = mysqli_fetch_object($sth)) {		
	
	foreach($r as $key => $value){
		echo "$key => $value <br/>";
	}
}
?>
