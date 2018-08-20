angular.module('myApp')
.service('userService',['$http','$q', function($http, $q) {
	this.register = function(data) {
		var defered = $q.defer();
		// console.log(data);
		
		$http.post('http://localhost:8000/api/users', data).then(function(res) {
			defered.resolve(res);
		}, function(err) {
			defered.reject(err);
		});
		console.log('signup');
		return defered.promise;
	}

	this.userExist = function(userName) {
		var defered = $q.defer();
		$http.get('http://localhost:8000/api/users/'+userName).then(function(res) {
			// console.log(res);
			defered.resolve(res);
		}, function(err) {
			defered.reject(err);
		});
		return defered.promise;
	}
}])