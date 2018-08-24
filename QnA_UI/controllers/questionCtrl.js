angular.module('myApp')
.controller('questionCtrl', ['$scope','questionService', '$state','$window','$rootScope',
	function($scope, questionService, $state, $window, $rootScope)
 {
	$scope.question={};
  	$scope.currentLoginUserID = $rootScope.currentLoginUserID;
	$scope.addQuestion = function() {
		if ($scope.currentLoginUserID == null) {
			alert('Please login first');
		} else {
			var data = {};
			data.user_id = $scope.currentLoginUserID;
			data.title = $scope.question.title;
			data.description = $scope.question.description;
			questionService.addQuestion(data).then(function(res) {
				if (res.data.staus == 1) {}
				$scope.newAddID = res.data.id;
				console.log(res.data.id);
				$scope.question = {};
				$state.go('home');
			})
		}
	}

}])