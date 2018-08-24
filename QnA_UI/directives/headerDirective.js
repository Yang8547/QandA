angular.module('myApp')
.directive('header', function() {
  var headerCtrl = ['$scope', '$window','$rootScope', '$location', 'userService', '$state',
  function($scope, $window, $rootScope, $location, userService, $state) {
  	$scope.currentLoginUser = $rootScope.currentLoginUser;
  	$scope.currentLoginUserID = $rootScope.currentLoginUserID;

    $rootScope.$on('currentLoginUser', function(event, data) {
  		$scope.currentLoginUser = data;
  	});

  	$rootScope.$on('currentLoginUserID', function(event, data) {
  		$scope.currentLoginUserID = data;
  	});

    $scope.isActive = function (viewLocation) {
      var active = (viewLocation === $location.path());
      return active;
    };

    $scope.signout = function() {
      userService.signout().then(function(res) {
        $window.localStorage.removeItem('userName');
        $window.localStorage.removeItem('id');
        $rootScope.currentLoginUser = null;
        $rootScope.currentLoginUserID =null;
        $rootScope.$broadcast('currentLoginUser', null);
        $rootScope.$broadcast('currentLoginUserID', null);
        $window.location.href='/';
      })
    };

    $scope.goAdd = function() {
      $state.go('question.add');
    }

  }];
  return {
    restrict:'A',
    controller: headerCtrl,
    templateUrl: 'view/header.html'
  }
})
