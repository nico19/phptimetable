function TeacherTimetable(){
	var me = this;
	this.readTable("teacher", function(d){me.teachers=d;});
	this.readTable("st_group", function(d){me.groups=d;});
	this.readTable("subject", function(d){me.subjects=d;});
	this.readTable("faculty", function(d){me.faculties=d;});
}

var _p = TeacherTimetable.prototype;

_p.readTable = function (table, assignFunc){
	var me = this;	
	$.ajax({
		type: "GET",
		url: Config.ALL_TABLE_SCRIPT,
		data: {table: table}
	  })
	  .done(function( dataString ) {		
			data = JSON.parse(dataString);
			dataTable = {};			
			data.forEach(function(e){
				dataTable[e.id] = e.value;
				//console.log(e);
			});
			assignFunc(dataTable);
	   })
	   .fail(function(msg){ 
			me.error("cannot load "+table+" list");
	   });
}

_p.print = function ($lessons){
	var me = this;
	var html = "";
	for($day=1; $day<=5; $day++) {					
		var $dayString = Config.DAYS[$day-1];
		if ($day in $lessons){
			var records = Object.keys($lessons[$day]).length*2;
			console.log('lessons[day].length='+records);
			html += "<tr><td rowspan=" + records +">" + $dayString + "</td>";
		} else {
			html += "<tr><td>" + $dayString + "</td></tr>";
			continue;
		}
		for(var $time in $lessons[$day]) {
			html+= "<td rowspan=2>" + $time + "</td>";
			for($week=1; $week<=2; $week++){
				$weekString = Config.ODD_EVEN[$week-1];
				html+= "<td>" + $weekString + "</td> <td>";
											
				if (! (	($week in $lessons[$day][$time]) ) ) {
								html+= "</td></tr>";
								continue;							
				}				
				$ll = $lessons[$day][$time][$week];	
				console.log("lessons:" + $ll);
				if ($ll) {					
					$ll.forEach(function($lesson) {					
						html+= me.formatLesson($lesson);
						console.log("lesson:" + JSON.stringify($lesson));
					});					
				}
				html+= "</td></tr>";
			}
		}	
	}
	document.getElementById("timetable").innerHTML = html;
}

_p.formatLesson = function (e){
	return this.teachers[e.teacher]+
			this.subjects[e.subject] + ", "+
			this.groups[e.st_group] + "("+ 
			this.faculties[e.faculty]+") в а."+
			e.room + "<br>";
}

_p.error = function(msg){
	alert(msg);
};

_p.addAutocomplete = function (id){
	var me = this;
	$( "#"+Config.AUTOCOMPLETE_ID +id ).autocomplete({
	  source: function(request, response)
	  {
		  var matches = [];
		  for (var id in me.teachers){
			var value = me.teachers[id];
			if(value.indexOf(request.term)>=0){
				matches.push(value);
			}
		  }
		  response(matches);
	  }
	});
}

