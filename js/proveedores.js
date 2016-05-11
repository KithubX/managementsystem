var app = angular.module("appProveedores",['ui.router','toaster','ngAnimate','angularSpinner','jcs-autoValidate','ngBootbox','angularUtils.directives.dirPagination','ui.bootstrap']);

app.service("ProveedoresService",function($http,$state,$ngBootbox,toaster){

	var self = {
		"isLoading":false,
		"ordering":"name",
		"proveedores":[],
		"error":false,
		"search":null,
		"selectedSuplier":null,
		"DeleteSuplier":function(suplier){
			if(suplier==null)
			{
				toaster.pop('error',"Favor de seleccionar un Proveedor");	
			}else{
				$ngBootbox.confirm('¿Desea eliminar al proveedor: '+suplier.nb_proveedor+'?')
			    .then(function() {
			        // DEleting the user.
			        self.isLoading = true;
			       $http.get("http://localhost/managementsystem/modules/index.php/deleteSuplier",{params:{suplier:suplier.id}}).then(
					 	function(response){
					 		self.isLoading = false;
					 		var error = response.data.error;
					 		if(error!=1)
					 		{
					 			console.log(response.data);
					 			self.proveedores = response.data.info;
					 		}else{toaster.pop('error',response.data.mensaje);	}
					 	},
					 	function(data){
					 		self.error     = true;
					 		self.isLoading = false;
					 		toaster.pop('error',data.statusText);	
					 	}
					 )
			    }, function() {
			        console.log('Confirm dismissed!');
			    });
			}
		},
		"GetSupliers":function(){
			if(!self.isLoading)
			{
				self.isLoading = true;
				$http({method: "get",url:"http://localhost/managementsystem/modules/index.php/proveedores",data: $.param({}), 
				  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				 .then(
				 	function(response){
				 		
				 		var Data = response.data;
				 		if(Data.error != 1){
				 			self.isLoading = false;
				   			self.proveedores = Data.info;
				   			console.log(self.proveedores);
				   		}else{
				   			self.error     = true;
				 			self.isLoading = false;
				   			toaster.pop('error',Data.mensaje);	
				   		}
				   		//self.usersType = data;
				 	},
				 	function(data){
				 		self.error     = true;
				 		self.isLoading = false;
				 		toaster.pop('error',data.statusText);	
				 	}
				 )
			}
		}
	};
	return self;
});


app.controller("proveedoresController",function($scope,ProveedoresService,$state){
	$scope.ProveedoresService = ProveedoresService;
	$scope.currentPage        = 1; // Página actual, para paginación
	$scope.pageSize 	      = 5; // Tamaño de la página, para paginación.
	$scope.ProveedoresService.GetSupliers();

	$scope.SelectSuplier = function(suplier)
	{
		$scope.ProveedoresService.selectedSuplier = suplier;
	}

	$scope.RedirectAdd = function()
	{
		$state.go("addSuplier");
	}
});

app.controller("RegisterSuplierController",function($scope,ProveedoresService){
	$scope.title   			  = "Registrar Proveedor";
	$scope.ProveedoresService = ProveedoresService;
});

