var flowerNotes = angular.module('flowerNotes', ['ngRoute', 'ui.bootstrap','flowerTools']);



flowerNotes.config(function ($routeProvider) {
    //rootPath
    var basePath = rootPath + "bundles/flowercore/js/angular/notes/";

    /* app */
    $routeProvider.when('/', {templateUrl: basePath + 'view/index.html', controller: 'NotesController'});

    $routeProvider.otherwise({redirectTo: '/'});

}).run();
