
var App = angular.module('mission-next', ['ngRoute','ui.date'], function ($interpolateProvider, $httpProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');

    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded;charset=utf-8';

    /**
     * The workhorse; converts an object to x-www-form-urlencoded serialization.
     * @param {Object} obj
     * @return {String}
     */
    var param = function(obj) {
        var query = '', name, value, fullSubName, subName, subValue, innerObj, i;

        for(name in obj) {
            value = obj[name];

            if(value instanceof Array) {
                for(i=0; i<value.length; ++i) {
                    subValue = value[i];
                    fullSubName = name + '[' + i + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value instanceof Object) {
                for(subName in value) {
                    subValue = value[subName];
                    fullSubName = name + '[' + subName + ']';
                    innerObj = {};
                    innerObj[fullSubName] = subValue;
                    query += param(innerObj) + '&';
                }
            }
            else if(value !== undefined && value !== null)
                query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
        }

        return query.length ? query.substr(0, query.length - 1) : query;
    };

    // Override $http service's default transformRequest
    $httpProvider.defaults.transformRequest = [function(data) {
        return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
    }];
   });

var Routing = {
    config : { urlPrefix : '/dashboard/ajax', templateUrlPrefix: '/project/views/partials'},

    buildUrl : function(url){

        return this.config.urlPrefix + url;
    },

    buildTemplateUrl : function(url){

        return this.config.templateUrlPrefix + url;
    }
};



String.prototype.ucfirst = function(){

  return this.toString().toLowerCase().replace(/\b[a-z]/g, function(letter) {
        return letter.toUpperCase();
    });
};

App.directive('modalDialog', function() {
    return {
        restrict: 'E',
        scope: {
            show: '=',
            configurator: '='
        },
        replace: true, // Replace with the template below
        transclude: true, // we want to insert custom content inside the directive
        link: function(scope, element, attrs) {

            scope.dialogStyle = {};
            if (attrs.width)
                scope.dialogStyle.width = attrs.width;
            if (attrs.height)
                scope.dialogStyle.height = attrs.height;
            scope.hideModal = function() {
                //scope.configurator.app.is_active = !scope.configurator.app.is_active;
                scope.show = false;

                scope.configurator.call(scope);
            };
        },
        template: " <div class='ng-modal' ng-show='show'>"+
                    "<div class='ng-modal-overlay' ng-click='hideModal()'></div>"+
                    "<div class='ng-modal-dialog model-content' ng-style='dialogStyle'>"+
                    "<div class='ng-modal-dialog-content' ng-transclude></div></div></div>"
    };
});

App.directive('focusIf', [function () {
    return function focusIf(scope, element, attr) {
        scope.$watch(attr.focusIf, function (newVal) {
            if (newVal) {
                scope.$evalAsync(function() {
                    element[0].focus();
                });
            }
        });
    };

}]);

App.directive('ngReallyClick', [function() {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            element.bind('click', function() {
                var message = attrs.ngReallyMessage;
                if (message && confirm(message)) {
                    console.log(attrs.ngReallyClick);
                    scope.$apply(attrs.ngReallyClick);
                }
            });
        }
    }
}]);

App.directive('ngConfirmClick', [
    function(){
        return {
            priority: -1,
            restrict: 'A',
            link: function(scope, element, attrs){
                element.bind('click', function(e){
                    var message = attrs.ngConfirmClick;
                    if(message && !confirm(message)){
                        e.stopImmediatePropagation();
                        e.preventDefault();
                    }
                });
            }
        }
    }
]);











