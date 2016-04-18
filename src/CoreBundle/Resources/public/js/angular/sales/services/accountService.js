angular.module('flowerSales')
  .factory('AccountService', function ($q,$http) {
    var serviceUrl = rootPath + 'p/api/clients/accounts';
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
      },
      save: function (originalAccount){        
        account = angular.copy(originalAccount);
        if(account.activity !== undefined){
          account.activity = account.activity.id;
        }
        var deferred = $q.defer();
        $http.post(serviceUrl+'/new',account).then(
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
