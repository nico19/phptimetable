<?php
$charset = "utf8";
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


?>