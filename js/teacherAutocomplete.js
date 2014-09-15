var AUTOCOMPLETE_ID = "autocomplete";

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
	
	self.lessons = ko.observableArray();
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
			msg = msg.rows;
			msg.forEach(function(e){console.log(e);});
			self.lessons(msg);
		  })
		.fail(function(msg){ 
			self.lessons([msg]);
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
