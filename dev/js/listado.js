var app = angular.module('myApp', ['ui.bootstrap']);
            
app.filter('startFrom', function() {
    return function(input, start) {
        if(input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});
app.controller('customersCrtl', function ($scope, $http, $timeout) {
    $http.get(paginaResultados).success(function(data){
        $scope.list = data;
        $scope.currentPage = 1; //current page
        $scope.entryLimit = 5; //max no of items to display in a page
        $scope.filteredItems = $scope.list.length; //Initially for no filter  
        $scope.totalItems = $scope.list.length;
    });
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.filter = function() {
        $timeout(function() { 
            $scope.filteredItems = $scope.filtered.length;
        }, 10);
    };
    $scope.sort_by = function(predicate) {
        $scope.predicate = predicate;
        $scope.reverse = !$scope.reverse;
    };
});



$('#confirmDelete').on('show.bs.modal', function (e) {
    $message = $(e.relatedTarget).attr('data-message');
    $(this).find('.modal-body p').text($message);
    $title = $(e.relatedTarget).attr('data-title');
    $(this).find('.modal-title').text($title);

    $id_eliminar = $(e.relatedTarget).attr('data-id-eliminar');
    $url_eliminar = $(e.relatedTarget).attr('data-url-eliminar');
});

$('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
    $.ajax(
    {
        type: "POST",
        url: paginaResultados,
        data: "id="+$id_eliminar+"&oper=del",
        dataType : 'json',
        success: function(respuesta){
            window.location.href=$url_eliminar;
        }
    });
});

function abrir(pag)
{
    window.location.href= pag;
}