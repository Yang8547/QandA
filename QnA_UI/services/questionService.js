angular.module('myApp')
.service('questionService',['$http','$q', function($http, $q) {
	
	this.addQuestion = function(data) {
		var defered = $q.defer();
		$http.post('http://localhost:8000/api/questions', data).then(function(res) {
			defered.resolve(res);
		}, function(err) {
			defered.reject(err);
		});
		return defered.promise;
	}
}])