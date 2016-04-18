angular.module('flowerSearch').controller("SearchController", function ($scope, $routeParams, $http, Search,$window, $timeout) {
    $scope.loading = true;
    $scope.search = Search;
    
    $scope.toogleSearchInput = function(){
    	$scope.showSearchInput = !$scope.showSearchInput;
    	if($scope.search.text != ""){
    		$scope.search.searching = $scope.showSearchInput;
    	}
    	if($scope.showSearchInput){
    		 var element = $window.document.getElementById("navbar-search-input");
		     if(element){
    		  $timeout(function() {
		          element.focus();
		        })
		    }
    	}
    }
    $scope.closeSearch = function (){
    	$scope.showSearchInput = false;
    	$scope.search.searching = false;
    }
    $scope.search.searching = false;
    
});
angular.module('flowerSearch').controller("SearchResultController", function ($scope, $routeParams, $http,Search) {
	$scope.search = Search;
    var apiPath = rootPath+ "p/api/";
	$scope.$watch('search.text', function(newValue, oldValue) {
		if(newValue != "" && newValue !== undefined){
			$scope.findAll(newValue);
		}
		$scope.search.searching = (newValue != "" && newValue != undefined);
    });
    $scope.findAll = function (searchText) {
        var promise = $http.get(apiPath + 'search/',{params: {search: searchText}});
        promise.then(
            function (response) {
                $scope.tasks = response.data.tasks;
                $scope.notes = response.data.notes;
                $scope.projects = response.data.projects;
                $scope.accounts = response.data.accounts;
                $scope.contacts = response.data.contacts;
                $scope.events = response.data.events;
            });
    };
});

angular.module('flowerSearch').factory('Search',function(){
  return {text:'',searching:false};
});
