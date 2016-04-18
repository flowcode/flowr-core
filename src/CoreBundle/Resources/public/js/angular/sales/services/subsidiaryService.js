angular.module('flowerSales')
  .factory('SubsidiaryService', function ($q,$http) {
    var serviceUrl = rootPath + 'p/api/clients/subsidiaries';
    return {
      findAll: function (account) {
        var deferred = $q.defer();
        $http.get(serviceUrl+"/"+account.id).then(
            function (data) {
            deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      },
      save: function (subsidiary){
        var deferred = $q.defer();
        $http.post(serviceUrl+'/new',subsidiary).then(
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