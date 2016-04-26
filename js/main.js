'use strict';
var app = angular.module("app",['ui.router','toaster','ngAnimate','angularSpinner']);

app.directive("ccSomething",function(){
	return{
		'transclude':true,
		'restrict':'AE',
		'templateUrl':"templates/spinner.html",
		'scope':{
			'isLoading':'=',
			'message':'@'
		}
	}
});

app.directive("ccSpinner",function(){
	return{
		'transclude':true,
		'restrict':'AE',
		'templateUrl':"templates/spinner.html",
		'scope':{
			'isLoading':'=',
			'message':'@'
		}
	}
});

app.service("usersService",function($http,toaster){
	var self = {
		"selectedUser": null,
		"isLoading":false,
		"search":null,
		"ordering":"name",
		"users":[],
		"userImg":"css/img/default.png",
		"getUsers":function(){
			if(!self.isLoading)
			{
				self.isLoading = true;
				$http({method: "get",url:"http://localhost/management/modules/index.php/users",data: $.param({}), 
				  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				 .success(function(data, status, headers, config) 
				 {          	
				 		self.isLoading = false;
				   		console.log(data);
				   		self.users = data.users;
				  })  
				 .error(function(data, status, headers, config){
				 	$methodsService.alerta(2,"algo falló, disculpe las molestias");
				 });

			}
		}
	};	
	self.getUsers();
	return self;
});


app.controller("MainController",function($scope,$http,toaster,usersService,$rootScope){
	toaster.pop('success',"Welcome!");	
});

app.controller("usersController",function($scope,toaster,usersService,$state){
	$scope.UsersService = usersService;
	$scope.loader = true;

	$scope.SelectUser = function(user)
	{
		if(usersService.selectedUser == user)
		{
			usersService.selectedUser = null;
		}else{usersService.selectedUser = user;}
	}

	$scope.RedirectAdd = function()
	{
		$state.go("RegisterUser");
	}
});

app.controller("ViewController",function($scope,usersService){
	$scope.userImg = usersService.userImg;
});

app.controller("beautifulController",function($scope,toaster){
	$scope.showVideo = false;
	$scope.PressButton = function()
		{
			if($scope.showVideo)
			{
				$scope.showVideo = false;
				toaster.pop('error',"Oh No you hide the video :(");
				toaster.pop('error',"I'll be back!");
			}else{
				$scope.showVideo = true;
				toaster.pop('success',"You Have Pressed The Button!!");
				toaster.pop('success',"Now hear Arnold singing to you!");
			}
			
		}
	toaster.pop('success',"Welcome to the page of why you're the most beautiful girl on this planet.");
});

app.config(function($stateProvider,$urlRouterProvider){
	$stateProvider
	.state('list',{
		url:'/',
		templateUrl:'templates/main.html',
		controller:'MainController'
	})

	.state('users',{
		url:'/users',
		templateUrl:'templates/users.html',
		controller:'usersController'
	})

	.state('beautiful',{
		url:'/beautiful',
		templateUrl:'templates/beautiful.html',
		controller:'beautifulController'
	})

	.state('RegisterUser',{
		url:'/RegisterUser',
		templateUrl:'templates/RegisterUser.html',
		controller:'RegisterUserController'
	});

	

	$urlRouterProvider.otherwise('/');
});

app.controller("RegisterUserController",function($scope,usersService){
	alert("yo yo");
});