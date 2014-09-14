<!DOCTYPE html>
<html>
<head>
<title>Cайт Піговського Ю.Р.</title>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="styles.css" />
</head>
<body> 
<table>
<?php 
$charset = "utf8";


$teachers = [
	"Блашків%О.%В",
	"Гурик%М.%І",
	"Юрчишин%Т.%В.%",
	"Якубовський%Р.%В",
	"Добротвор%І.%Г.%",
	"Бубняк",
	"Палій%І.%О.%",
	"Дорош%В.%І.%",
	"Лукащук-Федик%С.%В",
	"Саченко%А.%О.%",
	"Римар%О.%Л.%",	
	"Файфура",	
	"Коваль",	
	"Майків",
	"Биковий",
	"Турченко%І.",
	"Гладій",
	"Ляпандра",
	"Пукас",
	"Яцків%Н.",
	"Пасічник%Р.%М.",
	"Турченко%В.",
];

$times = [
	"08:00:00",
	"09:35:00",
	"11:10:00",
	"12:50:00",
	"14:25:00",
	"16:00:00",
	"17:35:00",
	"19:00:00",
	"20:15:00"
];

$days=[
"Понеділок",
"Вівторок",
"Середа",
"Четвер",
"П'ятниця"
];

$oddEven=[
"чисельник",
"знаменник"
];

$dbname = "timetable";
$user = "root";
$pwd="";
$host ="localhost";
$con=mysqli_connect($host,$user,$pwd,$dbname);

// Check connection
if (mysqli_connect_errno()) {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  return;
}
mysqli_query ($con,"set character_set_client='$charset'"); 
mysqli_query ($con,"set character_set_results='$charset'"); 
mysqli_query ($con,"set collation_connection='".$charset."_general_ci'"); 
//print_r (getLesson($con, 1, 2, "11:10:00", "Піговський"));



for($day=1; $day<=5; $day++) {	
	$dayString = $days[$day-1];
	print "<tr><td rowspan=". count($times)*2 ."> $dayString </td>";
	foreach ($times as $time) {
		print "<td rowspan=2>$time</td>";
		foreach([1, 2] as $week){
			$weekString = $oddEven[$week-1];
			print "<td>$weekString </td> <td>";
			
			$lessons=[];
			foreach ($teachers as $teacher) {
			if ($lesson = getLesson($con, $week, $day, $time, $teacher)){ 	
					$lessons[] = $lesson; 
				}
			}
			//$lessons = array_unique($lessons);
			if ($lessons) {
				foreach($lessons as $lesson) {
					$t = $lesson["teacher"];
					$r = $lesson["room"];
					print "<p>$t в а.$r</p>";
				}
			}
			print "</td></tr>";
		}
	}	
}

function getLesson($con, $week, $day, $time, $teacher) {
	$sth = mysqli_query($con,"SELECT teacher,room from timetable where week=$week and day=$day and lesson_time='$time' and teacher like '%$teacher%'");	//
	
	if($sth && $r = mysqli_fetch_assoc($sth)) {
		
		return $r;
	}
}

?>
</table>
</body>
</html>