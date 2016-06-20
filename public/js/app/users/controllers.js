(function(){
    angular.module('users.controllers',[])
        .controller('UserController',['$window','$scope', '$routeParams','$location','crudService','socketService' ,'$filter','$route','$log',
            function($window,$scope, $routeParams,$location,crudService,socket,$filter,$route,$log){
                $scope.users = [];
                $scope.user = {};
                $scope.generos = [{name:'Masculino'},{name:'Femenino'}];
                //$scope.errors = [{val:'hola'}];
                $scope.errors;
                $scope.success;
                $scope.query = '';
                $scope.roles = [{key1:'1',value1:'Administrador'},{key1:'2',value1:'Cajero'}];
                $scope.user.role_id = '2';
                $scope.estados = [{key:'0',value:'Deshabilitado'},{key:'1',value:'Habilitado'}];
                $scope.user.estado = '1';
                $scope.showChange = false;

                $scope.changePass = function(){
                    $scope.showChange = !$scope.showChange;
                }
                

                $scope.changePass1 = function(){
                    //alert("hola mundo");
                    if($scope.user.password===$scope.user.password_confirmation){
                    crudService.update($scope.user,'editPasword').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $window.location.href="/auth/logout";
                                //$location.path('/users');
                            }else{
                                $scope.errors =data;
                            }
                        });
                }else{
                    alert("Las contraseña no coinciden ingrese nuevamente??");
                    $scope.user.password="";
                    $scope.user.password_confirmation="";
                    //$scope.showChange = !$scope.showChange;
                }
                }

                $scope.toggle = function () {
                    $scope.show = !$scope.show;
                };

                $scope.pageChanged = function() {
                    if ($scope.query.length > 0) {
                        crudService.search('users',$scope.query,$scope.currentPage).then(function (data){
                        $scope.users = data.data;
                    });
                    }else{
                        crudService.paginate('users',$scope.currentPage).then(function (data) {
                            $scope.users = data.data;
                        });
                    }
                };


                var id = $routeParams.id;

                if(id)
                {
                    crudService.byId(id,'users').then(function (data) {
                        $log.log(data);
                        $scope.user = data;

                    });
                    crudService.select('users','stores').then(function (data){
                        $scope.stores = data;
                    });
                }else{
                    crudService.paginate('users',1).then(function (data) {
                        $scope.users = data.data;
                        $scope.maxSize = 5;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                        $scope.itemsperPage = 15;

                    });
                    crudService.select('users','stores').then(function (data){
                        $scope.stores = data;
                       // $scope.user.store_id;
                        $scope.user.store_id = '1';
                    });
                }

                socket.on('user.update', function (data) {
                    $scope.users=JSON.parse(data);
                });

                $scope.searchUser = function(){
                if ($scope.query.length > 0) {
                    crudService.search('users',$scope.query,1).then(function (data){
                        $scope.users = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }else{
                    crudService.paginate('users',1).then(function (data) {
                        $scope.users = data.data;
                        $scope.totalItems = data.total;
                        $scope.currentPage = data.current_page;
                    });
                }
                    
                };



                $scope.createUser = function(){

                    if ($scope.userCreateForm.$valid) {
                        var f = document.getElementById('userImage').files[0] ? document.getElementById('userImage').files[0] : null;
                        //alert(f);
                        var r = new FileReader();
                        r.onloadend = function(e) {
                            alert(e.target.result);
                            $scope.user.image = e.target.result;

                            crudService.create($scope.user, 'users').then(function (data) {
                                if (data['estado'] == true) {
                                    $scope.success = data['nombres'];
                                    alert("creado Correctamente usuario");
                                    $location.path('/users');

                                } else {
                                    $scope.errors = data;
                                    //alert(data);

                                }
                            });
                        }
                        if(!document.getElementById('userImage').files[0]){
                        crudService.create($scope.user,'users').then(function (data){
                            if (data['estado'] == true) {
                                $scope.success = data['nombres'];
                                alert("creado Correctamente usuario");
                                $location.path('/users');

                            } else {
                                $scope.errors = data;

                            }
                        });}

                        if(document.getElementById('userImage').files[0]){
                            r.readAsDataURL(f);
                        }

                    }
                }
                
                $scope.editUser = function(row){
                    $location.path('/users/edit/'+row.id);
                };

                $scope.updateUser = function(){
                    if ($scope.userCreateForm.$valid) {
                        var f = document.getElementById('userImage').files[0] ? document.getElementById('userImage').files[0] : null;
                        //alert(f);
                        var r = new FileReader();
                        r.onloadend = function(e) {
                            $scope.user.image = e.target.result;
                        crudService.update($scope.user,'users').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/users');
                            }else{
                                $scope.errors =data;
                            }
                        });
                        }
                        if(!document.getElementById('userImage').files[0]){
                        crudService.update($scope.user,'users').then(function(data)
                        {
                            if(data['estado'] == true){
                                $scope.success = data['nombres'];
                                alert('editado correctamente');
                                $location.path('/users');
                            }else{
                                $scope.errors =data;
                            }
                        });}

                        if(document.getElementById('userImage').files[0]){
                            r.readAsDataURL(f);
                        }
                    }
                };

                $scope.deleteUser = function(row){
                    $scope.user = row;
                }

                $scope.cancelUser = function(){
                    $scope.user = {};
                }

                $scope.destroyUser = function(){
                    crudService.destroy($scope.user,'users').then(function(data)
                    {
                        if(data['estado'] == true){
                            $scope.success = data['nombre'];
                            $scope.user = {};
                            //alert('hola');
                            $route.reload();

                        }else{
                            $scope.errors = data;
                        }
                    });
                }
            }]);
})();
