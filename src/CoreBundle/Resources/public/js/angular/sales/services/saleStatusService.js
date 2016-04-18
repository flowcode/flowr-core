angular.module('flowerSales')
  .factory('SaleStatusService', function ($q,$http) {
    var serviceUrl = rootPath + 'p/api/sales/salestatus';
    return {
      findAll: function () {
        var deferred = $q.defer();
        $http.get(serviceUrl).then(
            function (data) {
            deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      }
    };
  });
