var COLORS=[
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


var AUTOCOMPLETE_ID = "autocomplete";

var $times = [
		"08:00",
		"09:35",
		"11:10",
		"12:50",
		"14:25",
		"16:00",
		"17:35",
		"19:00",
		"20:15"
	];

var $days=[
		"Понеділок",
		"Вівторок",
		"Середа",
		"Четвер",
		"П'ятниця"
	];	

var $oddEven=[
		"чисельник",
		"знаменник"
	];

function TeacherTimetable(){
	var me = this;
	
	$.ajax({
	  type: "GET",
	  url: 'allTeachers.php' 
	  })
	  .done(function( msg ) {		
			me.teachers = JSON.parse(msg);
			me.teachers.forEach(function(e){
				console.log(e);
	     });
	   })
	   .fail(function(msg){ 
			me.error("cannot load teacher list");
	   });
}

var _p = TeacherTimetable.prototype;

_p.print = function ($lessons){
	var html = "";
	for($day=1; $day<=5; $day++) {	
		$dayString = $days[$day-1];
		html += "<tr><td rowspan=" + $times.length*2 +">" + $dayString + "</td>";
		$times.forEach(function ($time) {
			html+= "<td rowspan=2>" + $time + "</td>";
			for($week=1; $week<=2; $week++){
				$weekString = $oddEven[$week-1];
				html+= "<td>" + $weekString + "</td> <td>";
				
				if (! (($day in $lessons) &&
						($time in $lessons[$day]) &&
							($week in $lessons[$day][$time+":00"]) ) ) {
								html+= "</td></tr>";
								continue;							
				}
				
				$ll = $lessons[$day][$time][$week];			
				if ($ll) {					
					$ll.forEach(function($lesson) {					
						html+= $lesson;
					});					
				}
				html+= "</td></tr>";
			}
		});	
	}
	document.getElementById("timetable").innerHTML = html;
}

_p.error = function(msg){
	alert(msg);
};

_p.addAutocomplete = function (id){
	var me = this;
	$( "#autocomplete" +id ).autocomplete({
	  source: function(request, response)
	  {
		  var matches = [];
		  me.teachers.forEach(function(t){
			if(t.value.indexOf(request.term)>=0){
				matches.push(t.value);
			}
		  });
		  response(matches);
	  }
	});
}

