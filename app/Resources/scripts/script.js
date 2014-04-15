// alert('veikia');

////////////////////////
// angular script
////////////////////////

var app = angular.module('gurmeApp',[]);

app.controller("MainController", function($scope, $http){
    $scope.data = "I now understand how the scope works!";
    $scope.ingredientsTextArea = ' 1/2 cup butter ';

//    var postData = "Ingredientas";
    $scope.ingredientCheck = function() {
        $scope.buttonName = "Checking...";
        $http({
            url: 'http://gurme.dev/app_dev.php/data/recipe/ingredientCheck',
            method: "POST",
            data: $.param({ "ingredients" : $scope.ingredientsTextArea }),
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        }).success(function (data, status, headers, config) {
            $scope.data = angular.fromJson(data);
//            $scope.ingredients = null;
            $scope.ingredients = $scope.data.ingredients;
            $scope.status = $scope.data.status;
            $scope.buttonName = "Submittion check";
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    }
});