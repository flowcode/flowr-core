var flowerSales = angular.module('flowerSales', ['ngRoute', 'ui.bootstrap','flowerTools','ui.select','toaster','ngAnimate','pascalprecht.translate','dialogs.main']);



flowerSales.config(function ($routeProvider) {
    //rootPath
    var basePath = rootPath + "bundles/flowercore/js/angular/sales/";

    /* app */
    $routeProvider.when('/', {templateUrl: basePath + 'view/sale.html', controller: 'SalesController'});
	$routeProvider.when('/edit/:id', {templateUrl: basePath + 'view/sale.html', controller: 'SalesController'});
    $routeProvider.otherwise({redirectTo: '/'});

}).run();
flowerSales.config(['$translateProvider', function ($translateProvider) {
    // Simply register translation table as object hash
   $translateProvider.translations('es',{
          DIALOGS_ERROR: "Error",
          DIALOGS_ERROR_MSG: "Se ha producido un error.",
          DIALOGS_CLOSE: "Cerrar",
          DIALOGS_PLEASE_WAIT: "Espere por favor",
          DIALOGS_PLEASE_WAIT_ELIPS: "Espere por favor...",
          DIALOGS_PLEASE_WAIT_MSG: "Completando operación.",
          DIALOGS_PERCENT_COMPLETE: "% Completado",
          DIALOGS_NOTIFICATION: "Notificación",
          DIALOGS_NOTIFICATION_MSG: "Notificación de una aplicación desconocida.",
          DIALOGS_CONFIRMATION: "Confirmación",
          DIALOGS_CONFIRMATION_MSG: "Se requiere confirmacion.",
          DIALOGS_OK: "Aceptar",
          DIALOGS_YES: "Sí",
          DIALOGS_NO: "No",
        });
    $translateProvider.preferredLanguage('es');
}]);