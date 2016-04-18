angular.module('flowerSales')
  .factory('SaleService', function ($q,$http) {
    var serviceUrl = rootPath + 'p/api/sales';
    function getSaveableSale(originalSale){
        sale = angular.copy(originalSale);
        sale.account = sale.account.id;
        sale.contact = sale.contact.id;
        sale.status = sale.status.id;
        sale.owner = undefined;
        sale.sale_items.forEach(function(element, index, array){
            if(element.type.id == 1){
                element.service = undefined;
            }else{
                element.product = undefined;
            }
            if(element.product && element.units > 0){
                element.product = element.product.id;
            }
            if(element.service && element.units > 0){
                element.service = element.service.id;
            }
            element.type = undefined;
            element.unitPrice_edited = undefined;
            element.id = undefined;
        });
        return sale;
    }
    return {
      save: function (originalSale){
        var sale = getSaveableSale(originalSale);
        var deferred = $q.defer();
        $http.post(serviceUrl+"/create",sale).then(
            function (data) {
            deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      },
      edit: function (originalSale){
        var sale = getSaveableSale(originalSale);
        var deferred = $q.defer();
        $http.put(serviceUrl+"/edit/"+originalSale.id,sale).then(
            function (data) {
             deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      },
      getById: function (id){
        var deferred = $q.defer();
        $http.get(serviceUrl+"/sale/"+id).then(
            function (data) {
                data.data.paymentmethod = data.data.paymentmethod.id;
                data.data.account.businessName = data.data.account.business_name;
                data.data.category = data.data.category.id;
                data.data.sale_items.forEach(function(element, index, array){
                    element.type = {};
                    if(element.service !== undefined){
                        element.type.id = 2;
                    }
                    if(element.product !== undefined){
                        element.type.id = 1;
                    }
                });
                deferred.resolve(data);
            },
            function (data){
                deferred.reject(data);
            });
        return deferred.promise;
      },
      isValid: function (sale){
        var response = {isValid: true, errors: []};
        if(sale.paymentmethod === undefined || sale.paymentmethod.id <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener un modo de pago.");
        }
        if(sale.category === undefined || sale.category.id <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener un plan de cuenta.");
        }
        if(sale.circuit === undefined || sale.circuit <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener un circuito.");
        }
        if(sale.account === undefined || sale.account.id <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener por lo menos una empresa.");
        }
        if(sale.contact === undefined || sale.contact.id <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener por lo menos un contacto.");
        }
        if(sale.sale_items === undefined || sale.sale_items.length <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener por lo menos un producto.");
        }
        if(sale.status === undefined || sale.status.id <= 0){
            response.isValid = false;
            response.errors.push("El pedido debe tener un estado válido.");
        }
        return response;
      }
    };
  });