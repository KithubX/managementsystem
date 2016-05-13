var app = angular.module("appProveedores",['ui.router','toaster','ngAnimate','angularSpinner','jcs-autoValidate','ngBootbox','angularUtils.directives.dirPagination','ui.bootstrap']);

app.service("ProductService",function($http,$state,$ngBootbox,toaster){
	var self = {
		"isLoading":false,
		"ordering":"name",
		"products":[],
		"error":false,
		"search":null,
		"selectedProduct":null,
		"screenLocation":null,
		"suplregister":null,
		"DeleteProduct":function(Product){
			if(Product==null)
			{
				toaster.pop('error',"Favor de seleccionar un producto");	
			}else{
				$ngBootbox.confirm('¿Desea eliminar el producto: '+Product.nb_producto+'?')
			    .then(function() {
			        // DEleting the user.
			        //self.isLoading = true;
			       $http.get("http://localhost/managementsystem/modules/index.php/deleteProduct",{params:{Product:Product.id}}).then(
					 	function(response){
					 		self.isLoading = false;
					 		var error = response.data.error;
					 		if(error!=1)
					 		{
					 			toaster.pop('success',"Producto eliminado");
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
		"GetProducts":function(){
			if(!self.isLoading)
			{
				self.isLoading = true;
				$http({method: "get",url:"http://localhost/managementsystem/modules/index.php/getProducts",data: $.param({}), 
				  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				 .then(
				 	function(response){
				 		
				 		var Data = response.data;
				 		if(Data.error != 1){
				 			self.isLoading = false;
				   			self.products = Data.info;
				   			console.log(self.products);
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



app.service("ProveedoresService",function($http,$state,$ngBootbox,toaster){

	var self = {
		"isLoading":false,
		"ordering":"name",
		"proveedores":[],
		"error":false,
		"search":null,
		"selectedSuplier":null,
		"screenLocation":null,
		"suplregister":null,
		"GoSupliers":function(){
			$state.go("proveedores");
		},	
		"ValidateSuplier":function(name){
			return $http.get("http://localhost/managementsystem/modules/index.php/searchProviderbyname",{params:{name:name}})
		},
		"SaveSuplier":function(suplier){
			if(!self.isLoading)
			{
				self.isLoading = true;
				self.ValidateSuplier(suplier.nb_proveedor).then(
					function(response)
					{
						var amount = (self.screenLocation=="Add")?response.data.info:0;
						if(amount==0)
						{	
							// Verificando si se edita o se guarda
			 				var send_method = (self.screenLocation=="Add")?"post":"put";
			 				var url_method  = (self.screenLocation=="Add")?"RegisterSuplier":"EditSuplier";
			 				var messageEnd  = (self.screenLocation=="Add")?"Proveedor Registrado!":"Proveedor Editado";
			 				var suplier_id  = (suplier.id!=undefined)?suplier.id:0;
			 				//Registrando al usuario.
			 				$http({method: send_method,url:"http://localhost/managementsystem/modules/index.php/"+url_method,
								data: $.param({"nb_proveedor":suplier.nb_proveedor,"desc_proveedor":suplier.desc_proveedor,"desc_address":suplier.desc_address,"num_telefono":suplier.num_telefono,"suplier_id":suplier_id}), 
							  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							})
							 .then(
							 	function(response){
							 		console.log(response);
							 		var Data = response.data;
							 		if(Data.error != 1){
							 			self.isLoading = false;
							 			toaster.pop('success',messageEnd);	
							 			self.suplregister = [];

							   		}else{
							   			self.error     = true;
							 			self.isLoading = false;
							   			toaster.pop('error',Data.mensaje);	
							   		}
							   		//self.usersType = data;
							 	},
							 	function(data){
							 		//self.error     = true;
							 		self.isLoading = false;
							 		toaster.pop('error',data.statusText);	
							 	}
							 )
						}else{
							self.isLoading = false;
				 			toaster.pop('error',"Este nombre de proveedor ya está registrado.")
						}
					},
					function(data)
					{
						self.error     = true;
				 		self.isLoading = false;
				 		toaster.pop('error',data.statusText);
					}
				);
			}
			
		},
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
					 			toaster.pop('success',"Proveedor eliminado");
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
		if(ProveedoresService.selectedSuplier == suplier)
			{
				ProveedoresService.selectedSuplier = null;
			}else{ProveedoresService.selectedSuplier = suplier;}
	}

	$scope.RedirectAdd = function()
	{
		$state.go("addSuplier");
	}

	$scope.RedirectEdit = function(id)
	{
		if(id==null)
		{
			toaster.pop('error',"Favor de seleccionar un Proveedor");	
		}else{
			$state.go("editSuplier",{id:id});
		}
	}
});

app.controller("RegisterSuplierController",function($scope,ProveedoresService){
	$scope.title   			  = "Registrar Proveedor";
	$scope.ProveedoresService = ProveedoresService;
	$scope.ProveedoresService.screenLocation = "Add";
	$scope.suplregister       = $scope.ProveedoresService.suplregister;
});

app.controller("editSuplierController",function($scope,ProveedoresService,$stateParams,$http,toaster){
	$scope.title   			  = "Editar Proveedor";
	$scope.ProveedoresService = ProveedoresService;
	$scope.ProveedoresService.screenLocation = "Edit";
	$scope.ProveedoresService.isLoading = true;
	$scope.id_suplier      	  = $stateParams.id;
	$http.get("http://localhost/managementsystem/modules/index.php/searchProviderById",{params:{id:$scope.id_suplier}}).then(
		function (response)
		{
	 		$scope.ProveedoresService.isLoading = false;
	 		$scope.suplregister = response.data.info[0];
	 		console.log($scope.userRegister);
	 	},
	 	function(data){
	 		self.error = true;
	 		toaster.pop('error',data.statusText);	
	 	}
	);
});

app.controller("productosController",function($scope,$http,ProductService){
	$scope.ProductService = ProductService;
	$scope.currentPage    = 1; // Página actual, para paginación
	$scope.pageSize 	  = 5; // Tamaño de la página, para paginación.
	$scope.ProductService.GetProducts();

	$scope.SelectProduct = function(product)
	{
		if(ProductService.selectedProduct == product)
			{
				ProductService.selectedProduct = null;
			}else{ProductService.selectedProduct = product;}
	}
});

