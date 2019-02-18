angular.module('myApp')
.controller('loginCtrl', ['$scope','userService', '$state','$window','$rootScope',function($scope, userService, $state, $window, $rootScope)
 {

	$scope.user={};
	console.log($window.localStorage.getItem('userName'));
	$scope.login = function() {
		userService.login($scope.user).then(function(res){
			if (res.data.status==1) {
				console.log('login');
				$window.localStorage.setItem('userName',res.data.name);
				$window.localStorage.setItem('id',res.data.id);
				$rootScope.currentLoginUser = res.data.name;
				$rootScope.currentLoginUserID = res.data.id;
				$rootScope.$broadcast('currentLoginUser', res.data.name);
				$rootScope.$broadcast('currentLoginUserID', res.data.id);
				$state.go('home');
			} else {
				$scope.invalid = true;
			}
		});
	};

}])
