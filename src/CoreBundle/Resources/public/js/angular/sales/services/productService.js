angular.module('flowerSales')
    .factory('ProductService', function ($q, $http) {
        var serviceUrl = rootPath + 'p/api/stock/products/forsale';
        return {
            findAll: function () {
                var deferred = $q.defer();
                $http.get(serviceUrl).then(
                    function (data) {
                        deferred.resolve(data);
                    },
                    function (data) {
                        deferred.reject(data);
                    });
                return deferred.promise;
            }
        };
    });
