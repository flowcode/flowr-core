var flowerKanban = angular.module('flowerKanban', ['ngRoute', 'ui.bootstrap', 'ui.sortable', 'ui.event','flowerTools', 'pusher-angular']);

flowerKanban.config(function ($routeProvider, $httpProvider) {
    var basePath = rootPath + "bundles/flowercore/js/angular/kanban/";

    /* app */
    $routeProvider.when('/list', {templateUrl: basePath + 'view/list.html', controller: 'TaskListController'});
    $routeProvider.when('/kanban', {templateUrl: basePath + 'view/kanban.html', controller: 'TaskKanbanController'});

    $routeProvider.otherwise({redirectTo: '/kanban'});

    $httpProvider.interceptors.push(['$q', '$location', function($q, $location) {
        return {
            'response': function(response) {
                if(typeof response.data === 'string' && response.data.indexOf("login-box-body")>-1) {
                    location.reload();
                    return $q.reject(response);
                }
                return response;
            }
        };
    }]);

}).run();
