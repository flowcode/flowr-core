var flowerImporter = angular.module('flowerImporter', ['ngRoute', 'mgo-angular-wizard','ngFileUpload']);

flowerImporter.config(function ($routeProvider) {
    var basePath = rootPath + "bundles/flowercore/js/angular/importer/";

    /* app */
    $routeProvider.when('/', {templateUrl: basePath + 'view/selectFile.html', controller: 'ImporterController'});

    $routeProvider.otherwise({redirectTo: '/'});

}).run();