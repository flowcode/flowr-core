angular.module('flowerKanban').controller('fullEditTaks', ['$scope', '$http', '$modalInstance', function($scope, $http, $modalInstance){
	$scope.doneEditingExtended = function () {
        $scope.taskEditExtended.filter_id = filterId;
        $http.post(rootPath + 'p/api/kanban/updateTaskExtended/' + $scope.taskEditExtended.id, $scope.taskEditExtended).then(function (response) {
            $scope.findAll();
        }, function (response) {
        });
        $scope.taskEditExtended = null;
        $modalInstance.close();
    };

}])