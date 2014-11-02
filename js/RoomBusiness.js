angular.module('timetableApp', [])
  .controller('RoomBusiness', ['$scope', function RoomBusiness($scope){
	$scope.data = [];

	$scope.setData = function(data){
		$scope.data = data;
	}
	
	$scope.loadRoomBusiness = function(){
		$.ajax({
			type: "GET",
			url: 'auditoriumFond.php',
		})
		.done(function( dataString ) {		
			$scope.setData(JSON.parse(dataString));									
		  })
		.fail(function(msg){ 
			alert("cannot read room business");
		} );
	};
	
	$scope.$days=[
		"Понеділок",
		"Вівторок",
		"Середа",
		"Четвер",
		"П'ятниця"
	];	

	$scope.$oddEven=[
		"чисельник",
		"знаменник"
	];
	
	$scope.loadRoomBusiness();
	
}]);

