var flowerTools = angular.module('flowerTools',[]);
flowerTools.directive('focusOn', function() {
   return function(scope, elem, attr) {
      scope.$on(attr.focusOn, function(e) {
          elem[0].focus();
      });
   };
});
flowerTools.directive('focusMe', function($timeout, $parse) {
  return {
    //scope: true,   // optionally create a child scope
    link: function(scope, element, attrs) {
      var model = $parse(attrs.focusMe);
      scope.$watch(model, function(value) {
        if(value === true) { 
          $timeout(function() {
            element[0].focus(); 
          },100);
        }
      });
    }
  };
});
flowerTools.filter('breakFilter', function () {
    return function (text) {
        if (text !== undefined) return text.replace(/\n/g, '<br />');
    };
});
flowerTools.filter('rawHtml', ['$sce', function ($sce) {
        return function (val) {
            return $sce.trustAsHtml(val);
        };
    }]);
flowerTools.filter('capitalize', function () {
    return function (input, scope) {
        if (input != null)
            input = input.toLowerCase();
        return input.substring(0, 1).toUpperCase() + input.substring(1);
    };
});

flowerTools.directive('boxPaginator', [function(){
	
	return {
            restrict: 'E',
            scope: {
                actualPage: "=",
                limit: "=",
                totalElements: "=",
                callbackFunction: "="
            },
             controller: ['$scope', function($scope) {
             	$scope.$watch('totalElements', function(newVal,oldval) {
             		update();
             	});
             	$scope.$watch('actualPage', function(newVal,oldval) {
             		update();
             	});
	            function update (){
	             	$scope.items = [];
	             	var lenght = ($scope.totalElements / $scope.limit);
	             	var totalPages =($scope.totalElements / $scope.limit);
	             	//no es necesario paginar
	             	if(lenght <= 1){
	             		return ;
	             	}
	             	var maxLength = 4;
	             	var startElement = 0;
	             	//is the first page
	             	if($scope.actualPage != 0){
		             	var item = {};
		            	item.label="«";
		            	item.class="";
		            	item.index=($scope.actualPage-1);
		            	$scope.items.push(item);
	             	}
	             	//there are more pages than we can show
	             	if((totalPages) > maxLength){
						lenght = maxLength;
	             	}
	             	if($scope.actualPage > maxLength - 2){
	             		lenght = parseInt(totalPages);
	             		startElement = lenght - maxLength;
	             	}
	            	for (var i = startElement; i < lenght; i++) {
	            		var item = {
	            					label: (i+1),
	            					class:"",
	            					index:i
	            				};
	            		if(i == $scope.actualPage){
	            			item.class="active";
	            		}
	            		$scope.items.push(item);
	            	}
	            	//is the last page
	            	if((parseInt($scope.actualPage)+1) != Math.ceil(totalPages)){
		            	var item = {
	            					label: "»",
	            					class:"",
	            					index:(parseInt($scope.actualPage)+1)
		            				};
		            	$scope.items.push(item);
	            	}
				}
	         }],
            template: function (element, attrs) {
            	var paginatior = '<ul class="pagination pagination-sm inline">';
            	paginatior += '<li ng-repeat="item in items" class="{{item.class}}"><a ng-click="callbackFunction(item.index)">{{item.label}}</a></li>';
				paginatior +='</ul>';
            	
            	
                return paginatior;
            },
            link: function (scope, el, attrs) {
            }
	};
}]);
flowerTools.directive('easedInput', function($timeout) {
        return {
            restrict: 'E',
            template: '<div><input id="{{id}}" focus-me="{{focus}}" class="{{externalClass}} my-eased-input" type="text" ng-model="currentInputValue" ng-change="update()" placeholder="{{placeholder}}"/>{{focus}}</div>',
            scope: {
                value: '=',
                timeout: '@',
                placeholder: '@',
                focus:'@',
                id: '@inputId',
                externalClass: '@inputClass'
            },
            transclude: true,
            link: function ($scope) {
                $scope.timeout = parseInt($scope.timeout);
                $scope.update = function () {
                    if ($scope.pendingPromise) { $timeout.cancel($scope.pendingPromise); }
                    $scope.pendingPromise = $timeout(function () { 
                      console.log("a");
                        $scope.value = $scope.currentInputValue;
                    }, $scope.timeout);
                };
            }
        }
    });



flowerTools.directive('richTextEditor', function() {
    return {
        restrict : "A",
        require : 'ngModel',
        transclude : true,
        link : function(scope, element, attrs, ctrl) {
            var textarea = $(element).wysihtml5();
            var editor = textarea.data('wysihtml5').editor;

            // view -> model
            editor.on('change', function() {
                scope.$apply(function() {
                    ctrl.$setViewValue(editor.getValue());
                });
            });

            // model -> view
            ctrl.$render = function() {
                textarea.html(ctrl.$viewValue);
                editor.setValue(ctrl.$viewValue);
            };

            /* - similar to above
            scope.$watch(attrs.ngModel, function(newValue, oldValue) {
                textarea.html(newValue);
                editor.setValue(newValue);
            });
            */

            // load init value from DOM
            ctrl.$render();
        }
    };
});