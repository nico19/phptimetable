var AUTOCOMPLETE_ID = "autocomplete";

var $times = [
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

function print($lessons){
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
							($week in $lessons[$day][$time]) ) ) {
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


function addAutocomplete(id){
	$( "#autocomplete" +id ).autocomplete({
	  source: function(request, response)
	  {
		$.ajax({
	  type: "GET",
	  //processData: false,
	  url: 'autocomplete.php',
	  //dataType: 'jsonp',
	  data: {
			teacher: request.term		
	  } 
	  })
	  .done(function( msg ) {
		//console.log(msg);
		msg = JSON.parse(msg);	
		/*msg.forEach(
			function(e)
			{
				console.log(e);
			}
		);*/
		response(msg);
	  })
	  .fail(function(msg)
	  { 
		response([msg])
	  } );
		
		
		
	  }
	});
}

function TeacherLessonsViewModel() 
{
	addAutocomplete(1);
	var self = this;
	self.teachersDIV = document.getElementById("teachers");
	self.teacherCount = 1;
	
	self.getTeachers = function(){
		teachers=[];
		for(var i=1; i<=self.teacherCount; i++) {
			teachers.push($('#'+AUTOCOMPLETE_ID+i).val());
		}
		return teachers;
	};
	
	self.setTeachers = function(teachers){
		for(var i=1; i<=self.teacherCount; i++) {
			$('#'+AUTOCOMPLETE_ID+i).val(teachers[i-1]);
		}
	};		
		
	
    self.readLessons = 
		function(){ 
			$.ajax({
				type: "GET",
				url: 'joint.php',
				data: {teacher: JSON.stringify(self.getTeachers()) } 
			})
		.done(function( msg ) {
			console.log(msg);
			msg = JSON.parse(msg);				
			//msg.forEach(function(e){console.log(e);});
			//self.lessons(msg);
			print(msg)
		  })
		.fail(function(msg){ 
			print([msg]);
		} );
	};
	
	self.addTeacher = function(){
		self.teacherCount++;
		var id = '"autocomplete'+self.teacherCount+'"';
		var teachers = self.getTeachers();
		self.teachersDIV.innerHTML+='<p><label for='+id+'>Викладач: </label>' +
			"<input id="+id+"/></p>";
		addAutocomplete(self.teacherCount);
		self.setTeachers(teachers);
	};
}

ko.applyBindings(new TeacherLessonsViewModel());
