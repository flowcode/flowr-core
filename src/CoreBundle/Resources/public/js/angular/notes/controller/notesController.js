
angular.module('flowerNotes').controller("NotesController", function ($scope, $routeParams, $http, $modal) {
    $scope.loading = true;
    var apiPath = rootPath+ "p/api/";
    $scope.findAll = function () {
        var promise = $http.get(apiPath + 'notes/'+type+'/' + accountId);
        promise.then(
                function (response) {
                    $scope.notes = response.data;
                    $scope.notespage = response.data.page
                    $scope.noteslimit = response.data.limit
                    $scope.notestotal = response.data.total
                    $scope.loading = false;
                }, function(response){console.log("error")});
    };

    $scope.createNote = function (note) {
        $scope.canSaveModal = false;
        $http.post(apiPath + 'notes/'+type+'/' + accountId, note).then(function (response) {
            $scope.findAll();
            modalInstance.close();
        }, function (response) {
            modalInstance.close();
        });
    };
    $scope.updateNote = function (note) {
        $scope.canSaveModal = false;
        $http.put(apiPath + 'notes/'+note.id, note).then(function (response) {
            $scope.findAll();
            modalInstance.close();
        }, function (response) {
            modalInstance.close();
        });
    };
    $scope.paginateNotes = function (page){
        console.log("page"+page);
        var promise = $http.get(apiPath + 'notes/'+type+'/' + accountId+'/'+page);
        promise.then(
                function (response) {
                    $scope.notes = response.data;
                    $scope.notespage = response.data.page;
                    $scope.noteslimit = response.data.limit;
                    $scope.notestotal = response.data.total;
                    $scope.loading = false;
                });
    };
    $scope.newNoteModal = function (task) {
        $scope.canSaveModal = true;
        $scope.newNote = {title:"",body:""};
        modalInstance = $modal.open({
            templateUrl: 'newNote.html',
            scope: $scope
        });
    };
    $scope.cancel = function (){
        modalInstance.close();
    };
    $scope.showNote = function (note){
        $scope.canSaveModal = true;
        $scope.titleEditing = false;
        $scope.bodyEditing = false;
        $scope.viewNote = angular.copy(note);
        modalInstance = $modal.open({
            templateUrl: 'viewNote.html',
            scope: $scope,
            backdrop : false
        });
    };
});


