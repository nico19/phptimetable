angular.module('timetableApp', [])
  .controller('RoomBusiness', ['$scope', function RoomBusiness($scope){
	
	$scope.Config = Config;
	
	$scope.data = [];

	$scope.setData = function(data){
		$scope.data = data;
	}
	
	$scope.showLesson = function(room, day, time, week){
		$.ajax({
			type: "GET",
			url: Config.SHOW_LESSON_SCRIPT,
			data: {room: room, day: day, time: time, week: week}
		})
		.done(function( dataString ) {					
			$('#lessonInfo').html(dataString).dialog();	
		  })
		.fail(function(msg){ 
			alert("cannot read lesson info");
		} );
	};
	
	$scope.loadRoomBusiness = function(){
		$.ajax({
			type: "GET",
			url: Config.ROOM_BUSINESS_SCRIPT,
		})
		.done(function( dataString ) {		
			$scope.setData(JSON.parse(dataString));									
		  })
		.fail(function(msg){ 
			alert("cannot read room business");
		} );
	};	
	
	$scope.loadRoomBusiness();
	
}]);

