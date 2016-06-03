angular.module('flowerSales').controller("SalesController", function ($scope, $routeParams, $http, $modal, AccountService, ContactService,PaymentMethodService,SaleService,ProductService, SaleStatusService, toaster, $q, dialogs, $translate, ActivityService, ServiceService, SaleCategoryService,$timeout, SubsidiaryService, FinanceAccountService) {
	
	$scope.paymentsMethods = [];
	$scope.products = [];
    $scope.accounts = [];
    $scope.loaded = false;
    $scope.focusItem = false;
    $scope.saving = false;
    $scope.circuitOne = 1;
    $scope.circuitTwo = 2;
    $scope.discountTypeProcentaje = 1;
    $scope.discountTypeNumber = 2;
    $scope.itemTypeProduct = 1;
	$scope.itemTypeService = 2;
	$scope.itemTypes = [{name:'Producto',id:$scope.itemTypeProduct}, {name:'Servicio',id:$scope.itemTypeService}];
	$scope.editable = true;

	$scope.loadData = function (){
		$q.all([
			ProductService.findAll(),
			AccountService.findAll(),
			PaymentMethodService.findAll(),
			SaleStatusService.findAll(),
			ServiceService.findAll(),
			SaleCategoryService.findAll(),
			FinanceAccountService.findIncomes(),
		]).then(function(data) {
			$scope.products = data[0].data;
			$scope.accounts = data[1].data;
			$scope.paymentsMethods = data[2].data;
			$scope.saleStatus =  data[3].data;
			$scope.services = data[4].data;
			$scope.saleCategorys = data[5].data;
			$scope.financeAccounts = data[6].data;
			$scope.loaded = true;
			if(!saleId){
				restartSale();
			}
		}, function(reason) {
				toaster.pop('error', "Error", "Se produjo un error al cargar la información.");
		});

		if(saleId){
			var salePromise = SaleService.getById(saleId);
			salePromise.then(function(data) {
				$scope.sale = data.data;
				watchItems();
				$scope.editable = true;// $scope.sale.status.sale_modificable;
			}, function(reason) {
				toaster.pop('error', "Error", "Se produjo un error al cargar los datos de la venta. Por favor intente nuevamente.");
			});
		}else{
			watchItems();
		}
	}
	function watchItems(){
		$scope.$watch('item.product', function(newValue, oldValue) {
			if( (newValue !== undefined && oldValue === undefined) ||
				(newValue !== undefined && (
				(newValue.id !== undefined && oldValue.id === undefined) ||
				(newValue.id != oldValue.id))) ){
				$scope.item.unit_price = $scope.item.product.sale_price;
				$scope.item.service = undefined;
			}
		},true);
		$scope.$watch('item.service', function(newValue, oldValue) {
			if( (newValue !== undefined && oldValue === undefined) ||
				(newValue !== undefined && (
				(newValue.id !== undefined && oldValue.id === undefined) ||
				(newValue.id != oldValue.id))) ){
				$scope.item.unit_price = $scope.item.service.price;
				$scope.item.product = undefined;
			}
		},true);
		$scope.$watch('sale.sale_items', function(newValue, oldValue) {
			if( (newValue !== undefined && oldValue === undefined) ||
				(newValue !== undefined && (
				(newValue.id !== undefined && oldValue.id === undefined) ||
				(newValue.id != oldValue.id))) ){
				if($scope.item.type == $scope.itemTypeProduct){
					$scope.item.unit_price = $scope.item.product.sale_price;
					$scope.item.service = undefined;
				}else{
					$scope.item.unit_price = $scope.item.service.price;
					$scope.item.product = undefined;
				}
			}
		},true);
	}
	$scope.$watch('sale', function(newValue, oldValue) {
		if(newValue !== undefined){
			if( newValue.account !== undefined && 
				((oldValue.account === undefined && newValue.account !== undefined) ||
		  		newValue.account.id != oldValue.account.id)){
				if($scope.sale.street === undefined){
					$scope.sale.street =  newValue.account.shipping_address;
				}
				$scope.sale.account.businessName =  newValue.account.business_name;
				var contactsPromise = ContactService.findAllByAccount(newValue.account.id);
				contactsPromise.then(function(data) {
					$scope.contacts = data.data;
				}, function(reason) {
					toaster.pop('error', "Error", "Se produjo un error al cargar los contactos.");
				});
				var subsidiariesPromise = SubsidiaryService.findAll(newValue.account);
				subsidiariesPromise.then(function(data) {
					$scope.subsidiaries = data.data;
				}, function(reason) {
					toaster.pop('error', "Error", "Se produjo un error al cargar las sucursales.");
				});	
	  		}
	  		updateTotals();
		}
	},true);
	$scope.subsidiary = {};
	$scope.$watch('subsidiary', function(newValue, oldValue) {
		if(newValue !== undefined && newValue != oldValue){
			$scope.sale.street = $scope.subsidiary.street;
			$scope.sale.street_number = $scope.subsidiary.street_number;
			$scope.sale.department = $scope.subsidiary.department;
			$scope.sale.locality = $scope.subsidiary.locality;
			$scope.sale.zip_code = $scope.subsidiary.zip_code;
			$scope.sale.city = $scope.subsidiary.city;
		}
	});

	$scope.item = {};
	$scope.addItem = function(item){
		if($scope.sale.sale_items === undefined){
			$scope.sale.sale_items = [];
		}
		if(((item.type.id == $scope.itemTypeProduct && item.product !== undefined) || (item.type.id == $scope.itemTypeService && item.service !== undefined)) && item.units !== undefined && item.units > 0){
			$scope.focusItem = false;
			var auxItem = angular.copy(item);
			$scope.sale.sale_items.push(auxItem);
			$scope.item = {};
			$scope.item.type = item.type;
    		$timeout(function(){$scope.focusItem = true;}, 100);
		}
	}

	$scope.removeItem = function(index){
	    $scope.sale.sale_items.splice(index, 1);
	}

	$scope.save = function (sale){
		var validation = SaleService.isValid(sale);
		if(!validation.isValid){
			showErrors(validation);
		}else{
			var warning = warnings();
			if(warning){
				warning.result.then(function (btn) {
					salePromise = saveSale(sale,afterSave);
				}, function (btn) {
		        	return false;
		        });
		    }else{
		    	$scope.saving = true;
		    	salePromise = saveSale(sale,afterSave);
		    }
		}
	}

	$scope.saveAndAdd = function (sale){
		var validation = SaleService.isValid(sale);
		if(!validation.isValid){
			showErrors(validation)
		}else{
			var warning = warnings();
			if(warning){
				warning.result.then(function (btn) {
	            	salePromise = saveSale(sale,function(){afterSave();restartSale();});
		        }, function (btn) {
		        	return false;
		        });				
			}else{
				salePromise = saveSale(sale,function(){afterSave();restartSale();});
			}
		}
	}
	$scope.activities = [];
	$scope.openCreateAccount = function (){
		$scope.newAccount = {};
		if($scope.activities.length <= 0){
			var activitiesPromies = ActivityService.findAll()
			activitiesPromies.then(function(data) {
				$scope.activities = data.data;
					modalInstance = $modal.open({
		            templateUrl: 'createAccount.html',
		            scope: $scope
		        });
			}, function(reason) {
				toaster.pop('error', "Error", "Se produjo un error al cargar las actividades.");
			});
			
		}else{
	        modalInstance = $modal.open({
	            templateUrl: 'createAccount.html',
	            scope: $scope
	        });
		}
	}
	$scope.createAccount = function (isValid){
		$scope.submitted = true;
        if (isValid) {
            var AccountPromise = AccountService.save($scope.newAccount);
            AccountPromise.then(function(data) {
				$scope.sale.account = data.data.entity;
            	var accountPromise = AccountService.findAll();
				accountPromise.then(function(data) {
					$scope.accounts = data.data;
					$scope.submitted = false;
					modalInstance.close();				
					$scope.newAccount = {};
					toaster.pop('success', "Guardado!", "Se ah creado la cuenta.");
				}, function(reason) {
					toaster.pop('error', "Error", "Se produjo un error al cargar las empresas.");
				});
			}, function(reason) {
				var errors = reason.data.errors;
				if(errors != undefined && errors.form.children.cuit != undefined &&
					errors.form.children.cuit.errors != undefined){
					$scope.cuitError = errors.form.children.cuit.errors[0];
				}
				toaster.pop('error', "Error", "Se produjo un error al guardado. Por favor verifique la información e intente nuevamente.");
			});
        }
	}
	$scope.openCreateSubsidiary = function (){
		$scope.newSubsidiary = {};
        modalInstance = $modal.open({
            templateUrl: 'createSubsidiary.html',
            scope: $scope
        });
	}
	$scope.createSubsidiary = function(isValid){
		$scope.submitted = true;
        if (isValid) {
            $scope.newSubsidiary.account = $scope.sale.account.id;
            var SubsidiaryPromise = SubsidiaryService.save($scope.newSubsidiary);
            SubsidiaryPromise.then(function(data) {
            	var SubsidiaryPromise = SubsidiaryService.findAll($scope.sale.account);
            	$scope.subsidiary = $scope.newSubsidiary;
            	$scope.subsidiary.id = data.data.entity.id;
				SubsidiaryPromise.then(function(data) {
					$scope.subsidiaries = data.data;
					toaster.pop('success', "Guardado!", "Se ha creado la sucursal.");
					$scope.newSubsidiary = {};
					$scope.submitted = false;
					modalInstance.close();
				}, function(reason) {
					toaster.pop('error', "Error", "Se produjo un error al cargar las sucursales.");
				});
				
			}, function(reason) {
				toaster.pop('error', "Error", "Se produjo un error al guardado. Por favor verifique la información e intente nuevamente.");
			});
        }
	}
	var modalInstance = undefined;
	$scope.openCreateContact = function (){
		$scope.newContact = {};
        modalInstance = $modal.open({
            templateUrl: 'createContact.html',
            scope: $scope
        });
	}
	$scope.createContact = function (isValid){
		$scope.submitted = true;
        if (isValid) {
            $scope.newContact.accounts = [$scope.sale.account.id];
            var ContactPromise = ContactService.save($scope.newContact);
            ContactPromise.then(function(data) {
            	var contactsPromise = ContactService.findAllByAccount($scope.sale.account.id);
            	$scope.sale.contact = $scope.newContact;
				$scope.sale.contact.id = data.data.entity.id;
				contactsPromise.then(function(data) {
					$scope.contacts = data.data;
					toaster.pop('success', "Guardado!", "Se ah creado el contacto.");
					$scope.newContact = {};
					$scope.submitted = false;
					modalInstance.close();
				}, function(reason) {
					toaster.pop('error', "Error", "Se produjo un error al cargar los contactos.");
				});
				
			}, function(reason) {
				toaster.pop('error', "Error", "Se produjo un error al guardado. Por favor verifique la información e intente nuevamente.");
			});
        }
	}
	function afterSave(){
		$scope.editable = true;//$scope.sale.status.sale_modificable; pendiente hasta que se arme bien
		$scope.saving = false;
	}
	function restartSale(){
		$scope.sale = {};
		$scope.editable = true;
		$scope.sale.category = $scope.saleCategorys[0].id;
		$scope.sale.circuit = $scope.circuitOne;
		$scope.sale.status = $scope.saleStatus[0];
	}
	function warnings(sale){
		if(($scope.item.product !== undefined || $scope.item.service !== undefined) && ($scope.item.units !== undefined || $scope.item.units !== null)){
			return dialogs.confirm("Productos no confirmados", "Se encontraron productos no confirmados. Para agregar los productos a la orden confirme los mismos. ¿Desea continuar de todas formas?", 'sm');
		}
		return false;
	}
	function saveSale(sale,successCallback){
		if(sale.id > 0){
			var salePromise = SaleService.edit(sale);
		}else{
			var salePromise = SaleService.save(sale);
		}
		salePromise.then(function(data) {
			$scope.sale.id = data.data.entity.id;
			$scope.item = {};
			toaster.pop('success', "Guardado!", "Se guardaron los datos correctamente.");
			if(successCallback !== undefined){
				successCallback(data);
			}
		}, function(reason) {
			toaster.pop('error', "Error", "Se produjo un error al guardado. Por favor verifique la información e intente nuevamente.");
		});
	}

	function showErrors(validation){
		var errors = [];
		angular.forEach(validation.errors, function(value, key) {
			errors.push("<li>"+value+"</li>");
		});
    	toaster.pop('error', "Error","<ul>"+errors.join("")+"</ul>", null, 'trustedHtml');
		return false;
	}
	function updateTotals(){
		var total = 0;
		var tax = 21;
		if($scope.sale.sale_items !== undefined){
			$scope.sale.sale_items.forEach(function(element, index, array){
				if((element.product || element.service ) && element.units > 0){
					element.total = element.unit_price * element.units;
					total += element.unit_price * element.units;
				}
			});
		}
		$scope.sale.total_discount = 0;
		if($scope.sale.discount){
			if($scope.sale.discount_type == $scope.discountTypeProcentaje){
				$scope.sale.total_discount = ((total * $scope.sale.discount) / 100);
			}else{
				$scope.sale.total_discount = $scope.sale.discount;
			}
		}
		$scope.sale.total =  total - $scope.sale.total_discount;
		if($scope.sale.circuit != $scope.circuitTwo){
			$scope.sale.tax = total * (tax/100);
		}else{
			$scope.sale.tax = 0;
		}
		$scope.sale.total_with_tax = $scope.sale.tax + total;
	}

});
angular.module('flowerSales').filter('nameIdFilter', function() {
  return function( items, search) {
    var filtered = [];
    var re = new RegExp(search, 'i');
    angular.forEach(items, function(item) {
      if(item.id.toString().match(re) || item.name.match(re) ) {
        filtered.push(item);
      }
    });
    return filtered;
  };
});