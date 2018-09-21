angular.module('myApp')
.service('homeService',['$http','$q', function($http, $q) {
	
	this.timeline = function() {
		var defered = $q.defer();
		$http.get('http://localhost:8000/api/timeline').then(function(res) {
			defered.resolve(res);
		}, function(err) {
			defered.reject(err);
		});
		return defered.promise;
	}
}])