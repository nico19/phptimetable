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
	"Файфура ",	
	"Коваль%В.%С.",	
	"Майків ",
	"Биковий ",
	"Турченко%І.",
	"Гладій ",
	"Ляпандра ",
	"Пукас ",
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

$lessons=[];
$color = 0;

foreach ($teachers as $teacher) {
	$colorString = $colors[$color];	
	foreach (getTeacherLessons($con, $teacher) as $lesson) {
		$t = $lesson->teacher;
		$r = $lesson->room;
		$lessons[$lesson->day][$lesson->lesson_time][$lesson->week][]="<p style='background-color:$colorString'>$t в а.$r</p>";
	}	
	$color++;
}


for($day=1; $day<=5; $day++) {	
	$dayString = $days[$day-1];
	print "<tr><td rowspan=". count($times)*2 ."> $dayString </td>";
	foreach ($times as $time) {
		print "<td rowspan=2>$time</td>";
		foreach([1, 2] as $week){
			$weekString = $oddEven[$week-1];
			print "<td>$weekString </td> <td>";
			
			if (! (array_key_exists($day, $lessons) &&
					array_key_exists($time, $lessons[$day]) &&
						array_key_exists($week, $lessons[$day][$time]) ) ) {
							print "</td></tr>";
							continue;							
			}
			
			$ll = $lessons[$day][$time][$week];			
			if ($ll) {
				if (is_array($ll)) {
					foreach(array_unique($ll, SORT_REGULAR) as $lesson) {					
						print $lesson;
					}
				} else {
					print $ll;
				}
			}
			print "</td></tr>";
		}
	}	
}

mysqli_close($con);

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
</table>
</body>
</html>