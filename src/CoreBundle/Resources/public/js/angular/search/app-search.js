var flowerSearch = angular.module('flowerSearch', ['ngRoute', 'ui.bootstrap','flowerTools','ngAnimate','pusher-angular']);


flowerSearch.config(function ($routeProvider) {
    //rootPath
    var basePath = rootPath + "bundles/flowercore/js/angular/search/";

    /* app */
    $routeProvider.when('/', {templateUrl: basePath + 'view/index.html', controller: 'SearchResultController'});

    $routeProvider.otherwise({redirectTo: '/'});

}).run();

angular.module('flowerSearch').controller('NotificationController', function($scope, $http, $pusher) {
    $scope.alerts = 0;
    $scope.notifications = [];

    $scope.loadAlerts = function(){
        var promise = $http.get(rootPath + 'p/api/users/notifications/unreads');
        promise.then(
                function (response) {
                    $scope.alerts = parseInt(response.data.count);
                });
    };

    $scope.setViewed = function(notification){
        if(!notification.viewed){
            $http.post(rootPath + "p/api/users/notifications/" + notification.id + "/viewed").then(function(){
                for(var i=0; i < $scope.notifications.length; i++){
                    var currNotif = $scope.notifications[i];
                    if(currNotif.id == notification.id){
                        currNotif.viewed = true;
                    }
                }
            }, function(){

            });
        }

    }

    $scope.showNotifications = function(){
        var promise = $http.get(rootPath + 'p/api/users/notifications');
        promise.then(
                function (response) {
                    $scope.notifications = response.data;
                    $scope.alerts = 0;
                });
    }

    $scope.loadAlerts();

    /* realtime updates */
    pusher.subscribe('notifications-user-'+userId);
    pusher.bind('new-notifications', function(data) {
        $scope.alerts += 1;
        $scope.$apply();
    });

});
angular.module('flowerSearch').directive("notificationsWidget", function() {
    var basePath = rootPath + "bundles/flowercore/js/angular/search/";
    return {
        restrict:'A',
        templateUrl: basePath + 'view/notificationsWidget.html'
    };
});
