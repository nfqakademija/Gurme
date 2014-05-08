// alert('veikia');

////////////////////////
// angular script
////////////////////////

var app = angular.module('gurmeApp',[]);

app.controller("MainController", function($scope, $http){
    $scope.data = "I now understand how the scope works!";
    $scope.ingredientsTextArea = ' 1/2 cup butter ';
    $scope.calories = '500';
    $scope.status = $("#ingredientsBlock").css("display");
    $("#recipeBlock").toggleClass("hidden");

    $("inputtt").on('keyup',function() {
        $('#btnSearch').html('aaaaaaaa');
    });

    $('#calories').keyup(function(e){
        if(e.keyCode == 13)
        {
            $scope.loadRecipes();
        }
    });

//    var postData = "Ingredientas";
    $scope.ingredientInputChange = function() {
        $("#btnSearch").html("bbbbbbbbcccccccc");
    }

    $scope.ingredientCheck = function() {
        $scope.buttonName = "Checking...";
        $http({
            url: document.location.href.replace(/\/$/,'') + '/ingredientCheck',
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

    $scope.loadRecipes = function() {

        $( "#recipeBlock" ).toggleClass( "hidden", false );
        $( "#inputDiv" ).css("margin-top","10px");
        if ($("#ingredientsBlock").css("display")=="block") {
            var ingredients = $('.chosen-select').chosen().val();
        }
        $http({
            url: document.location.href.replace(/\/$/,'') + '/list',
            method: "POST",
            data: $.param({"calories":$scope.calories , "ingredients":ingredients}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        }).success(function (data, status, headers, config) {
            $scope.data = angular.fromJson(data);
            $scope.recipes = $scope.data.recipes;
            var i = 0;
            for (var prop in $scope.recipes) {
                i = i + 1;
                if (0==i%4) {
                    $scope.recipes[prop].containerInsert = 'clearfix';
                }
            }
            $scope.status = $scope.data.status;
            $scope.buttonName = "Submittion check";
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    }


});

///////////////////////////
// Manto script'as [Ingredient SEARCH - AJAX]
///////////////////////////
// kas įvesta search'e -> this.get_search_text();

function displayIngredientsBlock()
{
    $("#orChooseIngredients").css("display", "none");
    $("#ingredientsBlock").css("display", "block");

    // first initialize the Chosen select
    $(".chosen-select").chosen({width:"100%"});
    // then, declare how you handle the change event
    $('.chosen-select').chosen().change(function(){
        var myValues = $('.chosen-select').chosen().val();
        // then do stuff with the array
        console.log(myValues);

    });

    var chosen = $(".chosen-select").chosen().data('chosen');

    $('.chosen-choices .search-field input').focus();

    $('.chosen-select').ajaxChosen({
        dataType: 'json',
        type: 'POST',
        url: document.location.href.replace(/\/$/,'') + '/queryIngredient/ajaxChosen',
        data: {'keyboard':'cat'}, //Or can be [{'name':'keyboard', 'value':'cat'}]. chose your favorite, it handles both.
        success: function(data, textStatus, jqXHR){
            // do somthng
        }
    },{
//        processItems: function(data){ return data.complex.results; },
//        useAjax: function(e){ return someCheckboxIsChecked(); },
//        generateUrl: function(q){ return '/search_page/'+somethingDynamical(); },
        loadingImg: 'http://http://gurme.dev/images/loading.gif'
//        minLength: 2
    });

}


