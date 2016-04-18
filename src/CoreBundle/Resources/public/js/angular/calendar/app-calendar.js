var flowerCalendar = angular.module('flowerCalendar', ['ngRoute', 'ui.bootstrap','ui.calendar']);

flowerCalendar.config(function ($routeProvider, $httpProvider) {
    var basePath = rootPath + "bundles/flowercore/js/angular/calendar/";

    /* app */
    $routeProvider.when('/', {templateUrl: basePath + 'view/calendar.html', controller: 'CalendarController'});

    $routeProvider.otherwise({redirectTo: '/'});

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
