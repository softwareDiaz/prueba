(function(){
    var app = angular.module('listServices',[
        'ngRoute',
        'btford.socket-io',
        'ngSanitize',
        'listServices.controllers',
        'crud.services',
        'routes',
        'ui.bootstrap'
    ]);
})();