var app= angular.module("applogin",['jcs-autoValidate','angular-ladda','angularSpinner','toaster','ngAnimate','ui.router']);

app.controller("LoginController",function($scope,$http,toaster){
	$scope.imageprofile = "assets/img/avatar_2x.png"
	$scope.user = [];
	$scope.isLoading = false;

	$scope.Login = function()
	{
		$scope.isLoading = true;

		// Doing the query to the server
		$scope.user.Action = "CheckUser";
		var params         = JSON.stringify($scope.user);
		// enviando la petición
		console.log($scope.user.password);
		$http.get("http://localhost/management/modules/index.php/login",{params:{name:$scope.user.name,password:$scope.user.password}})
				 .success(function(data, status, headers, config) 
				 {          	
				   		$scope.isLoading = false;
				   		if(!data.error)
				   		{
				   			if(data.info.length>0)
				   			{
				   				toaster.pop('success',"Welcome Sweetie :D");
				   				$scope.imageprofile = "css/img/mainbepa.jpg"
				   				toaster.pop('success',"Wait until you get to the main page");
				   				window.location = "index.php";
				   			}else{toaster.pop('error',"you're not allowed, you shall not pass!!"); $scope.isLoading = false;}
				   		}else{toaster.pop('error',"Whoopsie, something went wrong :("); $scope.isLoading = false;}
				  })  
				 .error(function(data, status, headers, config){
				 	$scope.isLoading = false;
				 	toaster.pop('error',"Whoopsie, something went wrong :(");
				 });
	}


	$scope.sleep = function(delay) {
    var start = new Date().getTime();
    while (new Date().getTime() < start + delay);
  }

});

app.run(
	function(defaultErrorMessageResolver){
		defaultErrorMessageResolver.getErrorMessages().then(function(errorMessages){
			errorMessages['CorrectPattern'] = "Write a valid user, don't write weird typos";
		});
	}
);

