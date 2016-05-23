var app = angular.module("appProveedores",['ui.router','toaster','ngAnimate','angularSpinner','jcs-autoValidate','ngBootbox','angularUtils.directives.dirPagination','ui.bootstrap']);

app.filter('PriceFilter',function(){
	return function(input,param){;
		console.log(input);
		console.log(param);
	}
});

app.service("buysService",function($http,$state,$ngBootbox,toaster){
	var self = {
		"isLoading":false,
		"ordering":"-fec_compra",
		"buys":[],
		"error":false,
		"search":null,
		"currentBuy":[],
		"screenLocation":null,
		"productRegister":null,
		"formModified":false,
		"searchDates":null,
		"dateStart":null,
		"dateEnd":null,
		"selectedBuy":null,
		"goBuys":function(){
			$state.go("buys");
		},
		"AsignPrice":function(id_prod,products){
			angular.forEach(products,function(entry){
				if(entry.id==id_prod)
				{
					self.currentBuy.price = entry.num_precio;
					if(self.currentBuy.num_cantidad!=undefined)
					{
						self.currentBuy.num_total = entry.num_precio*self.currentBuy.num_cantidad;
					}	
				}
			});
		},
		"findBuyById":function(id){
			$http.get("http://localhost/managementsystem/modules/index.php/findBuyById",{params:{id:id}}).then(
			 	function(response){
			 		var Data = response.data;
			 		if(Data.error==0)
			 		{
			 			self.currentBuy = Data.info[0];
			 			self.currentBuy.num_cantidad = parseInt(Data.info[0].num_cantidad);
			 			self.currentBuy.num_total 	 = parseInt(Data.info[0].num_total);
			 			self.currentBuy.Petition  	 = true;
			 			self.currentBuy.fec_compra   = new Date(self.currentBuy.fec_compra);
			 			self.isLoading  		  	 = false;
			 		}else{toaster.pop('error',Data.mensaje);}
			 	},
			 	function(data){
			 		self.error     = true;
			 		self.isLoading = false;
			 		toaster.pop('error',data.statusText);	
			 	}
			 )
		},
		"SaveBuy":function(buy){
			if(buy.num_total==undefined)
			{
				buy.num_total = buy.price*buy.num_cantidad;
			}
			
			if(buy.fec_compra != undefined)
			{
				// Insertar la compra
				buy.num_total   = buy.num_cantidad*buy.price;
				var send_method = (self.screenLocation=="Add")?"post":"put";
				var url_method  = (self.screenLocation=="Add")?"RegisterBuy":"EditBuy";
				var messageEnd  = (self.screenLocation=="Add")?"Compra Registrada!":"Compra Editada";
				var id_compra   = (buy.id!=undefined)?buy.id:0;
				buyDate         = buy.fec_compra.toISOString().slice(0,10).replace(/-/g,"-");
				buy.num_total   = buy.price*buy.num_cantidad;
				
				self.isLoading  = true;
				//Registrando al usuario.
				$http({method: send_method,url:"http://localhost/managementsystem/modules/index.php/"+url_method,
				data: $.param({"id_proveedor":buy.id_proveedor,"id_producto":buy.id_producto,"num_cantidad":buy.num_cantidad,"num_total":buy.num_total,"fec_compra":buyDate,"desc_compra":buy.desc_compra,"id_compra":id_compra}), 
				  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
				})
				 .then(
				 	function(response){
				 		var Data = response.data;
				 		if(Data.error==0)
				 		{
				 			self.formModified = true;
							toaster.pop('success',messageEnd);	
				 			self.isLoading  = false;
				 		}else{toaster.pop('error',Data.mensaje);}
				 	},
				 	function(data){
				 		self.error     = true;
				 		self.isLoading = false;
				 		toaster.pop('error',data.statusText);	
				 	}
				 )
			}else{toaster.pop('error',"Favor de seleccionar una fecha");}
			
		},
		"deleteBuy":function(buy){
			self.isLoading = true;
			$http.get("http://localhost/managementsystem/modules/index.php/deleteBuy",{params:{compra:buy.id,dateStart:self.dateStart,dateEnd:self.dateEnd}}).then(
			 	function(response){
			 		var Data = response.data;
			 		if(Data.error==0)
			 		{
			 			self.buys = Data.info
			 			self.isLoading = false;
			 		}else{toaster.pop('error',data.mensaje);}
			 	},
			 	function(data){
			 		self.error     = true;
			 		self.isLoading = false;
			 		toaster.pop('error',data.statusText);	
			 	}
			 )
		},
		"FindBuys":function(dates){
			if(dates!=null)
			{
				if(dates.start!=null && dates.end!= null)
				{
					self.dateStart = dates.start.toISOString().slice(0,10).replace(/-/g,"-")
					self.dateEnd   = dates.end.toISOString().slice(0,10).replace(/-/g,"-")
					if(!self.isLoading)
					{
						self.isLoading = true;
						 $http.get("http://localhost/managementsystem/modules/index.php/getBuys",{params:{dateStart:self.dateStart,dateEnd:self.dateEnd}}).then(
						 	function(response){
						 		
						 		var Data = response.data;
						 		if(Data.error != 1){
						 			self.isLoading = false;
						   			if(Data.info.length>0)
						   			{
						   				self.buys = Data.info;
						   				$state.go("buysDetail.buysTables");
						   			}else{toaster.pop('error',"No hay compras en ese rango de fechas");}
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
				}else{toaster.pop('error',"Favor de llenar ambas fechas");}
			}else{
				toaster.pop('error',"Favor de llenar ambas fechas");	
			}
		}

	};
	return self;
});

app.service("ProductService",function($http,$state,$ngBootbox,toaster,$rootScope){
	var self = {
		"isLoading":false,
		"ordering":"name",
		"products":[],
		"error":false,
		"search":null,
		"selectedProduct":null,
		"screenLocation":null,
		"productRegister":null,
		"formModified":false,
		"findProductsBySuplier":function(id){
			if(id!=undefined)
			{
				 $http.get("http://localhost/managementsystem/modules/index.php/getProductsBySuplier",{params:{suplier:id}}).then(
				 	function(response){
				 		Data = response.data;
				 		if(Data.error==0)
				 		{
				 			self.products = Data.info
				 		}else{toaster.pop('error',Data.mensaje);}
				 	},
				 	function(data){
				 		self.isLoading = false;
				 		toaster.pop('error',data.statusText);	
				 	}
				 )
			}
		},
		"ValidateProduct":function(name,proveedor){
			return $http.get("http://localhost/managementsystem/modules/index.php/searchProductbyname",{params:{name:name,proveedor:proveedor}})
		},
		"GoProducts":function(){
			$state.go("productos");
		},
		"saveProduct":function(product){
			if(!self.isLoading && product !=null)
			{
				self.isLoading = true;
				self.ValidateProduct(product.nb_producto,product.id_proveedor).then(
					function(response)
					{
						var amount = (self.screenLocation=="Add")?response.data.info:0;
						if(amount==0)
						{	
							// Verificando si se edita o se guarda
			 				var send_method = (self.screenLocation=="Add")?"post":"put";
			 				var url_method  = (self.screenLocation=="Add")?"RegisterProduct":"EditProduct";
			 				var messageEnd  = (self.screenLocation=="Add")?"Producto Registrado!":"Product Editado";
			 				var product_id  = (product.id!=undefined)?product.id:0;
			 				//Registrando al usuario.
			 				$http({method: send_method,url:"http://localhost/managementsystem/modules/index.php/"+url_method,
								data: $.param({"nb_producto":product.nb_producto,"desc_producto":product.desc_producto,"num_precio":product.num_precio,"id_proveedor":product.id_proveedor,"product_id":product_id}), 
							  headers: { 'Content-Type': 'application/x-www-form-urlencoded' }
							})
							 .then(
							 	function(response){
							 		var Data = response.data;
							 		if(Data.error != 1){
							 			self.isLoading = false;
							 			self.formModified = true;
							 			toaster.pop('success',messageEnd);	
							 			
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
				 			toaster.pop('error',"Este producto ya está registrado para este proveedor.")
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
		"formModified":false,
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
							 			self.isLoading    = false;
							 			self.formModified = true;
							 			toaster.pop('success',messageEnd);	
							 			self.suplregister = (self.screenLocation=="Add")?[]:self.suplregister;

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
	$scope.$watch("ProveedoresService.formModified",function(){
		if(ProveedoresService.formModified==true)
		{
			$scope.suplierForm.$setPristine();
			$scope.ProveedoresService.suplregister = [];
			ProveedoresService.formModified = false;
		}
	});
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
	 		$scope.ProveedoresService.suplregister = response.data.info[0];
	 	},
	 	function(data){
	 		self.error = true;
	 		toaster.pop('error',data.statusText);	
	 	}
	);
});

app.controller("productosController",function($scope,$http,ProductService,$state,toaster){
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

	$scope.RedirectAdd = function()
	{
		$state.go("addproduct");
	}

	$scope.RedirectEdit = function(id)
	{
		if(id==undefined)
		{
			toaster.pop('error',"Favor de seleccionar un Producto");	
		}else{
			$state.go("editProduct",{id:id});
		}
	}
});

app.controller("addproductController",function($scope,$http,ProductService,ProveedoresService){
	$scope.ProductService     = ProductService;
	$scope.ProveedoresService = ProveedoresService;
	$scope.title 			  = "Registrar Productos";
	$scope.ProductService.screenLocation = "Add";
	// Obteniendo los proveedores
	$scope.ProveedoresService.GetSupliers();
	$scope.formModified = $scope.ProductService.formModified;
	$scope.$watch("ProductService.formModified",function(){
		if(ProductService.formModified==true)
		{
			$scope.productForm.$setPristine();
			$scope.ProductService.productRegister = [];
			ProductService.formModified = false;
		}
	});	
});

app.controller("editProductController",function($scope,$http,ProductService,ProveedoresService){
	$scope.ProductService     = ProductService;
	$scope.ProveedoresService = ProveedoresService;
	$scope.title 			  = "Registrar Productos";
});

app.controller("editProductController",function($scope,$http,ProductService,ProveedoresService,toaster,$stateParams){
$scope.id_Product      	  = $stateParams.id;
$scope.ProductService     = ProductService;
$scope.ProveedoresService = ProveedoresService;
$scope.ProductService.isLoading = true;
$scope.ProveedoresService.GetSupliers();
$scope.formModified = $scope.ProductService.formModified;

$http.get("http://localhost/managementsystem/modules/index.php/searchProductById",{params:{id:$scope.id_Product}}).then(
		function (response)
		{
	 		$scope.ProductService.isLoading = false;
	 		$scope.ProductService.productRegister = response.data.info[0];
	 	},
	 	function(data){
	 		self.error = true;
	 		toaster.pop('error',data.statusText);	
	 	}
	);
});

app.controller("productsController",function($scope,$http,toaster,buysService,$state){
	$scope.buysService = buysService;
	  $scope.options = {
	    minDate: new Date('dd-MMM-yyyy'),
	    showWeeks: true
	  };
	  $scope.RedirectAdd = function()
	  {
	  	$state.go("addBuy");
	  }
});

app.controller("buysDetailController",function($scope,$http,toaster,buysService,$state){
	$scope.buysService = buysService;
	$scope.GoCompras = function()
	{
		$state.go("buys");
	}

	$scope.SelectView = function(view)
	{
		if(view=="list")
		{
			$state.go("buysDetail.buysTables");
		}else{$state.go("buysDetail.buysFrames");}
	}

});

app.controller("addBuyController",function($scope,$http,toaster,buysService,$state,ProveedoresService,ProductService){
	$scope.title 			  = "Registrar compra";
	$scope.buysService        = buysService;
	$scope.currentBuy         = buysService.currentBuy;
	$scope.buysService.currentBuy = null;
	$scope.ProveedoresService = ProveedoresService;
	$scope.ProductService     = ProductService;
	$scope.buysService.screenLocation = "Add";
	$scope.ProveedoresService.GetSupliers();


	$scope.$watch("buysService.formModified",function(){
		if(buysService.formModified==true)
		{
			$scope.buyForm.$setPristine();
			$scope.buysService.currentBuy = [];
			buysService.formModified      = false;
			console.log($scope.buysService.currentBuy);
		}
	});

	$scope.$watch('buysService.currentBuy.num_cantidad',function(){
		if($scope.currentBuy.num_cantidad>0 && $scope.price>0)
		{	
			$scope.buysService.currentBuy.num_total = $scope.price*$scope.currentBuy.num_cantidad;
		}
	});
});

app.controller("editBuyController",function($scope,$http,toaster,buysService,$state,ProveedoresService,ProductService,$stateParams){
	$scope.title 			  = "Editar compra";
	$scope.id_compra      	  = $stateParams.id;
	$scope.buysService        = buysService;
	$scope.buysService.findBuyById($scope.id_compra);
	$scope.currentBuy         = buysService.currentBuy;
	$scope.ProveedoresService = ProveedoresService;
	$scope.ProductService     = ProductService;
	$scope.ProductService.products    = $scope.ProductService.products;
	$scope.buysService.screenLocation = "Edit";
	$scope.ProveedoresService.GetSupliers();
	

	$scope.$watch("buysService.currentBuy",function(){
		if($scope.buysService.currentBuy.Petition == true)
		{
			$scope.buysService.currentBuy.Petition = false;
			$scope.ProductService.findProductsBySuplier($scope.buysService.currentBuy.id_proveedor);
		}
	});

	$scope.$watch("ProductService.products",function(){
		if($scope.ProductService.products.length>0)
		{
			angular.forEach($scope.ProductService.products,function(entry){
				if(entry.id == $scope.buysService.currentBuy.id_producto)
				{
					$scope.buysService.currentBuy.price = parseInt(entry.num_precio);
				}
			});
		}
	});
});