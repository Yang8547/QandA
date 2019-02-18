angular.module('myApp')
.controller('signupCtrl', ['$scope','userService', '$state',function($scope, userService, $state) {
	
	$scope.user={};

	$scope.signup = function() {
		userService.register($scope.user).then(function(res){
			console.log('register successfully!' , res.data.username, res.data.id);
			$state.go('login')
		});
	};
	
	// check user exist
	$scope.$watch(function() {
		return $scope.user.user_name;
	}, function(n, o) {
		if (n != o) {
			userService.userExist($scope.user.user_name).then(function(res) {
			// console.log(res);
			if (res.data.data==1) {
				$scope.userExist = true;
			} else {
				$scope.userExist = false;
			}
			});
		}
	}, true);

}])