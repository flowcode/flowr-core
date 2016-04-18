(function () {
    'use strict';

    angular.element(document)
        .ready(bootstrapAllModules);

    function bootstrapAllModules(){
        var allMultiElements = document.querySelectorAll('*[multi-app]');

        angular.forEach(allMultiElements, function(elem){
            var appName = angular.element(elem).attr('multi-app');
            angular.bootstrap(elem, [appName]);
        });
    }
}());