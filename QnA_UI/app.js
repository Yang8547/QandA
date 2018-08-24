'use strict';
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
		templateUrl: 'view/login.html',
		controller:'loginCtrl'
	})
	.state('signup', {
		url: '/signup',
		templateUrl: 'view/signup.html',
		controller: 'signupCtrl'

	})
}]);

app.run(['$rootScope','$window', function($rootScope,$window) {
	$rootScope.currentLoginUser = $window.localStorage.getItem('userName');
	$rootScope.currentLoginUserID = $window.localStorage.getItem('id');
}]);
