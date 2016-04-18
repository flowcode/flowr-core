angular.module('flowerSales')
  .factory('ContactService', function ($q,$http) {
    var serviceUrl = rootPath + 'p/api/clients/contacts';
    return {
      findAllByAccount: function (accountId) {
        var deferred = $q.defer();
        $http.get(serviceUrl+'/account/'+accountId).then(
            function (data) {
            deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      },
      save: function (contact){
        var deferred = $q.defer();
        $http.post(serviceUrl+'/new',contact).then(
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
