(function(){
    var app = angular.module('services',[
        'ngRoute',
        'btford.socket-io',
        'ngSanitize',
        'services.controllers',
        'crud.services.services',
        'routes',
        'ui.bootstrap'
    ]);
})();