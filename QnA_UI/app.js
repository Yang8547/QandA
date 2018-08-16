'use strict';

// Declare app level module which depends on views, and components
// angular.module('myApp', [
//   // 'ngRoute',
//   'ui.router'
//   // 'myApp.view2',
//   // 'myApp.version'
// ]).
// // config(['$locationProvider', '$routeProvider', function($locationProvider, $routeProvider) {
// //   $locationProvider.hashPrefix('!');

// //   // $routeProvider.otherwise({redirectTo: '/view1'});
// // }]);
var app = angular.module('myApp',['ui.router']);

app.config(['$stateProvider', '$urlRouterProvider', function($stateProvider, $urlRouterProvider) {
	$urlRouterProvider.otherwise('/home');

	$stateProvider
	.state('home', {
		url: '/home',
		templateUrl: 'view/home.html'
	})
	.state('login', {
		url: '/login',
		templateUrl: 'view/login.html'
	})
	.state('signup', {
		url: '/signup',
		templateUrl: 'view/signup.html'
	})
}])

app.controller('navCtrl', ['$scope', function($scope) {
	$scope.home = true;
	$scope.homeAct = function() {
		$scope.home = true;
		$scope.login = false;
		$scope.signup = false;
	};
	$scope.loginAct = function() {
		$scope.home = false;
		$scope.login = true;
		$scope.signup = false;
	};
	$scope.signupAct = function(){
		$scope.home = false;
		$scope.login = false;
		$scope.signup = true;
	}
}])