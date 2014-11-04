<?php 
include "static/head.html";
include "connect.php";
$id = $_REQUEST["id"];
$sth = mysqli_query($con,"SELECT timetable.day, timetable.lesson_time, timetable.week, timetable.room, teacher.value as teacherName, subject.value as subjectTitle, st_group.value as groupTitle FROM teacher, subject, st_group, timetable WHERE timetable.id=$id AND teacher.id=timetable.teacher AND subject.id=timetable.subject AND st_group.id=timetable.st_group");	
if (!$sth){
	echo mysql_errno($con) . ": " . mysql_error($con). "\n";
	return;
}

while($r = mysqli_fetch_object($sth)) {		
	
	foreach($r as $key => $value){
		echo "$key => $value <br/>";
	}
}


?>
</body>
</html>