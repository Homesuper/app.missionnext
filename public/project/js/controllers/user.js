(function(){
    var userControllers = angular.module('userControllers', ['ngSanitize', 'angularUtils.directives.dirPagination']);

    userControllers.config(usersRouter);




    function usersRouter($routeProvider){

        $routeProvider
//        .when(
//            '/', { templateUrl: Routing.buildUrl('/user/list'), controller: "UserListCtl" }
//        )
            .when(
            '/', { templateUrl: Routing.buildTemplateUrl('/users/list.html'), controller: "UserListCtl" }
        )
            .when(
            '/:user', { templateUrl: Routing.buildTemplateUrl('/users/user.html'), controller: "UserCtl" }
        );

    }

    function intersect(a, b) {
        var t;
        if (b.length > a.length) t = b, b = a, a = t; // indexOf to loop over shorter
        return a.filter(function (e) {
            if (b.indexOf(e) !== -1) return true;
        });
    }



    userControllers.controller("UserListCtl",['$scope', '$http', '$sce', '$timeout', 'filterFilter', function($scope, $http, $sce, $timeout, filterFilter){

        $scope.search = {};
        $scope.delayTimer;

        $scope.sortUser = {
            p : 'created_at',
            order : 0,
            getProperty  : function(){

                return this.p;
            },
            getOrder : function(){

                return this.order ? 'ASC' : 'DESC';
            },

            getCssClass: function(){

                return this.order ? 'glyphicon glyphicon-arrow-up' : 'glyphicon glyphicon-arrow-down';
            }
        };



        function buildFilterQuery(){

            return 'filters[sub_status]='+($scope.search.sub_status || '')+'&filters[status]='+($scope.search.status || '')+'&filters[role]='+($scope.search.role || '')+'&filters[app]='+($scope.search.app || '')+'&filters[profile]='+($scope.search.profile || '')+'&sort[p]='+$scope.sortUser.getProperty()+'&sort[o]='+$scope.sortUser.getOrder();
        };

        $scope.customFiltering = function() {
            clearTimeout($scope.delayTimer);
            $scope.delayTimer = setTimeout(function () {
                getResultsPage(1);
            }, 1000);
        };


        $('#role-select-id').selectize({
            plugins: ['remove_button'],
            delimiter: '|',
            maxItems: null,
            valueField: 'id',
            preload: true,
            openOnFocus: true,
            labelField: 'label',
            searchField: 'role',
            options: [],
            create: false,
            onChange: function(value){
                    $scope.search.role = value;
                    $scope.customFiltering();
            },
            initUrl: Routing.buildUrl('/filter/roles'),
            remoteUrl:Routing.buildUrl('/filter/roles'),
            load : function(query, callback){
                var selectize = this;
                $http.get(selectize.settings.initUrl).success(function(data){
                    $.each(data, function(idx, el){
                        selectize.addOption(el);
                    });
                });

            }
        });

        $('#status-select-id').selectize({
            plugins: ['remove_button'],
            delimiter: '|',
            maxItems: null,
            preload: true,
            openOnFocus: true,
            valueField: 'id',
            labelField: 'status',
            searchField: 'status',
            options: [
                {id: 1, status: 'Pending Approval'},
                {id: 2, status: 'Active' },
                {id: 3, status: 'Disabled'}
            ],
            create: false,
            onChange: function(value){
                    $scope.search.status = value;
                    $scope.customFiltering();
            }

        });
        $('#sub-status-select-id').selectize({
            plugins: ['remove_button'],
            delimiter: '|',
            maxItems: null,
            preload: true,
            openOnFocus: true,
            valueField: 'id',
            labelField: 'status',
            searchField: 'status',
            options: [
                {id: 'active', status: 'Active'},
                {id: 'grace', status: 'Grace' },
                {id: 'expired', status: 'Expired'}
            ],
            create: false,
            onChange: function(value){
                    $scope.search.sub_status = value;
                    $scope.customFiltering();
            }

        });

        $('#apps-select-id').selectize({
            plugins: ['remove_button'],
            delimiter: '|',
            maxItems: null,
            valueField: 'id',
            preload: true,
            openOnFocus: true,
            labelField: 'name',
            searchField: 'name',
            options: [],
            create: false,
            onChange: function(value){
                $scope.search.app = value;
                $scope.customFiltering();
            },
            initUrl: Routing.buildUrl('/filter/apps'),
            remoteUrl:Routing.buildUrl('/filter/apps'),
            load : function(query, callback){
                var selectize = this;
                $http.get(selectize.settings.initUrl).success(function(data){
                    $.each(data, function(idx, el){
                        selectize.addOption(el);
                    });
                });

            }
        });

        $scope.pagination = {
            current: 1
        };

        $scope.sort = function(property){
           $scope.sortUser.p = property;
           $scope.sortUser.order = !$scope.sortUser.order;
           $scope.pagination.current = 1;
           $scope.customFiltering();
        };

        getResultsPage($scope.pagination.current);

        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };

        function getResultsPage(pageNumber) {
            $scope.pagination.current = pageNumber;
            // this is just an example, in reality this stuff should be in a service
            $http.get(Routing.buildUrl('/user/list?page='+pageNumber+'&'+buildFilterQuery()))
                .success(function(result) {
                    $scope.totalUsers = result.totalUsers ? result.totalUsers : 1;
                    $scope.itemsPerPage = result.itemsPerPage;
                    $scope.users = result.users.data;
                    $.each($scope.users,function(idx, user){
                        $.each(user.appsIds, function(ix, id){
                            $scope.users[idx].appsIds[ix] = id.toString();
                        });
                    });

                });
        }

    }]);

    userControllers.controller("UserCtl",['$scope', '$routeParams', '$http', '$location', function($scope, $params, $http, $location){
        $scope.modalShown = false;
        $scope.modalCancelSub = false;
        $scope.modalDelete = false;

        $scope.toggleModal = function(isActive, sub) {

            $scope.isActiveOnSite = isActive ? { 'label' : 'Block', value: isActive, sub: sub } : { 'label' : 'Activate', value: isActive, sub: sub };
            $scope.configurator = function(){
                sub.app.is_active = !sub.app.is_active;
            };
            $scope.modalShown = !$scope.modalShown;
        };

        $scope.toggleModalCancelSub = function(sub){
            $scope.cancelSubModel = sub;
            $scope.modalCancelSub = !$scope.modalCancelSub;
        };

        $scope.transactions = [];

        $scope.closeModal = function (isActiveOnSite) {
            isActiveOnSite.sub.app.is_active = !isActiveOnSite.sub.app.is_active;
            $scope.modalShown = !$scope.modalShown;
        };
        $scope.closeModalCancelSub = function(){

            $scope.modalCancelSub = !$scope.modalCancelSub;
        };
        $scope.closeDelete = function () {

            $scope.modalDelete = !$scope.modalDelete;
        }

        $http.get(Routing.buildUrl('/subscription/manager/transactions/'+$params.user))
            .success(function(data){
                $scope.transactions = $.map(data, function(el) { return el; });
            }).error(function(error){
                console.log(error);
        });

        $scope.closeSubscription = function(sub){
            $scope.modalCancelSub = !$scope.modalCancelSub;

            if (sub.is_recurrent && sub.authorize_id){
                $.each($scope.subscriptions, function(idx, s){
                    s.status = 'closed';
                });
            }

            sub.status = 'closed';
            $scope.updateSub(sub, 'status', true);
        };

        $scope.activateOnSite = function(bool)
        {
            var status = bool && $scope.isActiveOnSite.value ? 'enable' : 'disable';
            if (bool && !$scope.isActiveOnSite.value){
                status = 'enable';
                bool = true;
                $scope.isActiveOnSite.value = true;
            }

            $scope.isActiveOnSite.sub.app.is_active = bool && $scope.isActiveOnSite.value;
            $http.get(Routing.buildUrl('/user/app/'+ status+'/'+$scope.isActiveOnSite.sub.user_id+'/'+$scope.isActiveOnSite.sub.app_id))
                .success(function(data){
                }).error(function(error){
                    console.log(error);
                });

            $scope.modalShown = !$scope.modalShown;

        };

        $scope.deleteRedirect = function()
        {
            window.location.href = '/dashboard/user/' + $params.user + '/delete';
            //$location.path($params.user + '/delete');
        }

        $scope.dateOptions = {
            changeYear: true,
            changeMonth: true,
            dateFormat: 'yy-mm-dd'
        };

        $scope.myDate = "Thursday, 11 October, 2012";

        $scope.user = null;
        $scope.subscriptions = [];
        $scope.showLevel = false;

        $http.get(Routing.buildUrl('/user/'+$params.user)).success(function(data){
            $scope.user = data.user;
            if ($scope.user.status != 1 &&  $scope.user.is_active){
                $scope.userStatusMessage = 'Access Granted';
            }else if($scope.user.status != 1 &&  !$scope.user.is_active){
                $scope.userStatusMessage = 'Access Denied';
            }
            $scope.showLevel = data.user.role === 'organization';
            $scope.statuses = data.statuses;
            //console.log($scope.user.profileData);
        });

        var watchPaid  = function(){

        };

        $http.get(Routing.buildUrl('/subscription/manager/'+$params.user)).success(function(data){
            $scope.subscriptions = data;
            angular.forEach($scope.subscriptions, function(sub, index){
                $scope.$watch(function(){
                    return $scope.subscriptions[index].paid;
                },function(val, old){
                   sub.paid = parseInt(val) || 0;
                });
            });
        });

        $scope.userStatusMessage = 'Pending Approval';

        $scope.setDisabled = function($event){
           $http.get(Routing.buildUrl('/user/disable/'+$scope.user.id))
               .success(function(data){
                    $scope.user.is_active = data.is_active;
                    $scope.user.status = data.status;
                    $scope.userStatusMessage = 'Access Denied';
               });
        };

        $scope.setActive = function($event){
            $http.get(Routing.buildUrl('/user/enable/'+$scope.user.id))
                .success(function(data){
                    $scope.user.is_active = data.is_active;
                    $scope.user.status = data.status;
                    $scope.userStatusMessage = 'Access Granted';
                });
        };

        $scope.updateSub = function(subscription, property, forceClose){
            $http.put(Routing.buildUrl('/subscription/'+ subscription.id), [{ field: property, value : subscription[property], forceClose : forceClose }])
                .success(function(data){
                    subscription.days_left = data.subscription.days_left;
                    subscription.status = data.subscription.status;
                });
        }

    }]);

    App.requires.push('userControllers');

})();



