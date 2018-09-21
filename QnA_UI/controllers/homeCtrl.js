angular.module('myApp')
.controller('homeCtrl', ['$scope','homeService',function($scope, homeService)
 {

 	$scope.items = []
 	homeService.timeline().then(function(res) {
 		console.log(res.data.data);
 		angular.forEach(res.data.data, function(v, k) {
 			if(v.hasOwnProperty('description')) {
 				v.type = "Question";
 			} else {
 				v.type = "Answer";
 			}
 			$scope.items.push(v);
 		})		 				
 		console.log($scope.items);
 	})
 	

}])