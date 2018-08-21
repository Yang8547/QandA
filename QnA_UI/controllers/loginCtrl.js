angular.module('myApp')
.controller('loginCtrl', ['$scope','userService', '$state',function($scope, userService, $state) {
	
	$scope.user={};

	$scope.login = function() {
		userService.login($scope.user).then(function(res){
			if (res.data.status==1) {
				console.log('login');
				$state.go('home');
			} else {
				$scope.invalid = true;
			}
		});
	};
	
}])