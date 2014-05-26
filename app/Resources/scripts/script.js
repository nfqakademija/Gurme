// alert('veikia');

////////////////////////
// angular script
////////////////////////

var app = angular.module('gurmeApp',[]);

app.controller("MainController", function($scope, $http){

    $scope.root = 'http://' + window.location.host.replace(/\/$/,'');
    $scope.root += (document.location.href.search('/app_dev.php') >= 0) ? '/app_dev.php' : '';

    $scope.data = "I now understand how the scope works!";
    $scope.ingredientsTextArea = ' 1/2 cup butter ';
    $scope.calories = '500';
    $scope.status = $("#ingredientsBlock").css("display");
    $("#recipeBlock").toggleClass("hidden");

    $scope.status = recipesJson;
    if (recipesJson!='')  {
        $( ".top-row" ).css("margin-top","0px");
        $scope.recipes = jQuery.parseJSON(recipesJson);
        var i = 0;
        for (var prop in $scope.recipes) {
            $scope.recipes[i].link = $scope.root + '/recipe/' + $scope.recipes[i].id;
            i = i++;
        }
    }

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
        $( "#loadingDiv" ).css("display","block");
        $( ".top-row" ).css("margin-top","0px");
        //$( "#inputDiv" ).css("margin-top","10px");
        if ($("#ingredientsBlock").css("display")=="block") {
            var ingredients = $('.chosen-select').chosen().val();
        }
        $http({
            url: $scope.root + '/list',
            method: "POST",
            data: $.param({"calories":$scope.calories , "ingredients":ingredients}),
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        }).success(function (data, status, headers, config) {
            $( "#loadingDiv" ).css("display","none");
            $scope.data = angular.fromJson(data);
            $scope.recipes = $scope.data.recipes;
            var i = 0;
            for (var prop in $scope.recipes) {
                $scope.recipes[i].link = $scope.root + '/recipe/' + $scope.recipes[i].id;
                i = i + 1;
                if (0==i%4) {
                    $scope.recipes[prop].containerInsert = 'clearfix';
                }
            }

            // checks if any recipes were found
            if(i==0){
                $( "#no_recipes").css("display", "block");
            }
            else{
                $( "#no_recipes").css("display", "none");

                $('html, body').animate({
                    scrollTop: $('#recipesList').offset().top - 60
                }, 1000);
            }

            $scope.status = $scope.data.status;
            $scope.buttonName = "Submittion check";
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    }

    $scope.toggleFavorite = function(recipe) {
        $("#toggleFavorite").html("Adding...");
        $http({
            url: $scope.root + '/recipe/'+recipe+'/toggleFavorite',
            method: "GET",
            headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'}
        }).success(function (data, status, headers, config) {
            $scope.data = angular.fromJson(data);
            $("#toggleFavorite").html($scope.data.r);
            switch ($scope.data.r) {
                case 'Redirecting...':
                    window.location = $scope.root + '/register';
                    break;
                case 'Added to favorites':
                    $("#toggleFavorite").removeClass('btn-default').addClass('btn-success');
                    break;
                case 'Removed from favorites':
                    $("#toggleFavorite").removeClass('btn-success').addClass('btn-default');
                    break;
            }
//            $scope.ingredients = null;
            $scope.buttonName = "Submittion check";
        }).error(function (data, status, headers, config) {
            $scope.status = status;
        });
    }

    $scope.toggleCategoryDiv = function() {
        if ($("#categories").is(':visible')) {
            if ($('#calorieInput > div').length>0 && $('#calorieInput > div').css('margin-top')!='0px') {
                var heightToSet = parseInt($('#calorieInput > div').css('margin-top').replace("px", "")) + $('#categories').height() + 10;
                console.log(heightToSet);
                $('#calorieInput > div').css('margin-top',heightToSet);
            }
            $("#categories").css('display','none');
            $("a[ng-click='toggleCategoryDiv()'] > span").attr('class', 'glyphicon glyphicon-chevron-down');
        } else {
            $('#categories').css('display','block');
            if ($('#calorieInput > div').css('margin-top')!='0px') {
                var heightToSet = parseInt($('#calorieInput > div').css('margin-top').replace("px", "")) - $('#categories').height() - 10;
                console.log(heightToSet);
                $('#calorieInput > div').css('margin-top',heightToSet);
            }
            $("html, body").animate({ scrollTop: 0 }, 1000);
            $("a[ng-click='toggleCategoryDiv()'] > span").attr('class', 'glyphicon glyphicon-chevron-up');
        }
    }

    $scope.toggleCategory = function(id,event) {
        $(event.target).css('font-weight','bold');
    }

    $scope.showCategory = function(category,event) {
        window.location = $scope.root + "/category/" + category;
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

function caloriesLessThan(calories) {
    angular.element($('body')).scope().calories = calories;
    angular.element($('body')).scope().loadRecipes();
}




