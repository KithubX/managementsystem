<?php 
	if(!defined("SPECIALCONSTANT")) die("acceso denegado");
	
	$app->get('/', function() use($app) 
	{
 	   $app->response->setStatus(200);
	    echo "Welcome to Slim 3.0 based API";
	}); 

	$app->get('/login/', function() use($app) 
	{
 	    	$data 	  = $app->request->get();
 	    	$user 	  = $data['name'];
 	    	$password = $data['password'];
 	    	$datos    = CheckUser($user,$password);
 	    	// Verificando si hay error
 	    	if($datos['error']!=1)
 	    	{	
 	    		$datos = array("info"=>count($datos['info']),"error"=>0,"mensaje"=>"");
 	    	}else{
 	    		$datos = $datos['mensaje'];
 	    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$datos["msj"]);
 	    	}
 	   		$app->response->body(json_encode($datos));
	}); 

	$app->get('/users/', function() use($app) 
	{
 	    	$users      = GetUsers();
 	    	$error      = $users['error'];
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	if($error==1)
 	    	{
 	    		$datos = $users['mensaje'];
 	    	}
 	    	else
 	    	{
 	    		$datos = array("users"=>$users['info']);
 	    	}
 	    	
 	    	$app->response->body(json_encode($datos));
 	   	
	}); 

	$app->get('/userstype/', function() use($app) 
	{
			$types      = GetTypeUsers();
 	    	$error      = $types['error'];
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	if($error==1)
 	    	{
 	    		$datos = $types['mensaje'];
 	    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$types["mensaje"]);

 	    	}
 	    	else
 	    	{
 	    		$datos = array("info"=>$types["info"],"error"=>0,"mensaje"=>"ok");
 	    	}
 	    	
 	    	$app->response->body(json_encode($datos));
 	   	
	}); 

	$app->get('/searchuserbyname/', function() use($app) 
	{
		// Tomando los datos
		$data = $app->request->get();
		$user = $data['name'];

		// Verificando que no existe el usuario
		$searchUser = buscarExistenciaPorUserName($user);
		if(count($searchUser)>0)
		{
			$userFinded = array("id"=>$searchUser['id'],"nb_user"=>$searchUser['nb_user'],
				"pw_password"=>$searchUser['pw_password'],"id_rol"=>$searchUser['id_rol']);
		}else{$userFinded = array();}
		$app->response->headers->set("Content-type","application/json");
 	    $app->response->setStatus(200);
 	    $app->response->body(json_encode($userFinded));
	});

	$app->get('/searchUserById/', function() use($app) 
	{
		// Tomando los datos
		$params    = $app->request->get();
		$id_user   = $params['id_user'];

		// Buscando el usuario por id.
		$data      = UserById($id_user);
		$error      = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	}); 

	$app->post("/RegisterUser/", function() use($app){
		$user = $app->request->post();
		// Registrando al usuario
		$data = RegistrarUsuario($user);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->put("/EditUser/", function() use($app){
		$user = $app->request->put();
		$data = EditarUsuario($user);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->get("/deleteUser/", function() use($app){
		$user = $app->request->get();
		$id   = $user['user'];
		$data = EliminarUsuario($id);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		// Buscando los usuarios restantes.
    		$users      = GetUsers();
 	    	$error      = $users['error'];
 	    	if($error!=0)
 	    	{
 	    		$info = "";
 	    	}else{$info = $users['info'];}
    		$datos = array("info"=>$info,"error"=>$error ,"mensaje"=>$users['mensaje']);
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	// URLS de proveedores
	$app->get('/proveedores/', function() use($app) 
	{
 	    	$data  = GetSupliers();
 	    	$error      = $data['error'];
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	if($error==1)
	    	{
	    		$datos = $data['mensaje'];
	    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

	    	}
	    	else
	    	{
	    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
	    	}
 	    	
 	    	$app->response->body(json_encode($datos));
 	   	
	}); 
	$app->get("/deleteSuplier/", function() use($app){
		$suplier = $app->request->get();
		$id   = $suplier['suplier'];
		$data = EliminarProveedor($id);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		// Buscando los usuarios restantes.
    		$users      = GetSupliers();
 	    	$error      = $users['error'];
 	    	if($error!=0)
 	    	{
 	    		$info = "";
 	    	}else{$info = $users['info'];}
    		$datos = array("info"=>$info,"error"=>$error ,"mensaje"=>$users['mensaje']);
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->get("/searchProviderbyname/", function() use($app){
		$data  = $app->request->get();
		$name  = $data['name'];
		$data  = GetSuplierByName($name);
		$error = $data['error'];

    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$amount = count($data['info']);
    		$datos = array("info"=>$amount,"error"=>1,"mensaje"=>$data["mensaje"]);
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->post("/RegisterSuplier/", function() use($app){
		$suplier = $app->request->post();
		// Registrando al usuario
		$data   = RegistrarProveedor($suplier);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});


	$app->get('/searchProviderById/', function() use($app) 
	{
		// Tomando los datos
		$params    = $app->request->get();
		$id        = $params['id'];

		// Buscando el usuario por id.
		$data      = SuplierById($id);
		$error      = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	}); 

	$app->put("/EditSuplier/", function() use($app){
		$suplier = $app->request->post();
		//Editando al usuario
		$data   = EditarProveedor($suplier);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	// Urls de productos de los proveedores.
	$app->get('/getProducts/', function() use($app) 
	{
 	    	$data  = GetProducts();
 	    	$error      = $data['error'];
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	if($error==1)
	    	{
	    		$datos = $data['mensaje'];
	    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

	    	}
	    	else
	    	{
	    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
	    	}
 	    	
 	    	$app->response->body(json_encode($datos));
 	   	
	});

    $app->get('/getProductsBySuplier/', function() use($app) 
    {
            $suplier = $app->request->get();
            $id   = $suplier['suplier'];
            $data  = getProductsBySuplier($id);
           $error      = $data['error'];
            $app->response->headers->set("Content-type","application/json");
            $app->response->setStatus(200);
            if($error==1)
            {
                $datos = $data['mensaje'];
                $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

            }
            else
            {
                $datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
            }
            
            $app->response->body(json_encode($datos));
        
    });

	$app->get("/deleteProduct/", function() use($app){
		$suplier = $app->request->get();
		$id   = $suplier['Product'];
		$data = EliminarProducto($id);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		// Buscando los usuarios restantes.
    		$users      = GetSupliers();
 	    	$error      = $users['error'];
 	    	if($error!=0)
 	    	{
 	    		$info = "";
 	    	}else{$info = $users['info'];}
    		$datos = array("info"=>$info,"error"=>$error ,"mensaje"=>$users['mensaje']);
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->get('/searchProductbyname/', function() use($app) 
	{
		// Tomando los datos
		$data    = $app->request->get();
		$product = $data['name'];
		$suplier = $data['proveedor'];
		// Verificando que no existe el usuario
		$searchProduct = searchProductbyname($product,$suplier);
		$amountProduct = count($searchProduct['info']);
		$productFinded = array("info"=>$amountProduct,"error"=>$searchProduct['error'] ,"mensaje"=>$searchProduct['mensaje']);
		
		$app->response->headers->set("Content-type","application/json");
 	    $app->response->setStatus(200);
 	    $app->response->body(json_encode($productFinded));
	});

	$app->post("/RegisterProduct/", function() use($app){
		$product = $app->request->post();
		// Registrando al producto
		$data   = RegistrarProducto($product);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->put("/EditProduct/", function() use($app){
		$product = $app->request->post();
		// Registrando al producto
		$data   = EditarProducto($product);
		$error          = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	});

	$app->get('/searchProductById/', function() use($app) 
	{
		// Tomando los datos
		$params    = $app->request->get();
		$id        = $params['id'];

		// Buscando el usuario por id.
		$data      = ProductById($id);
		$error      = $data['error'];
    	$app->response->headers->set("Content-type","application/json");
    	$app->response->setStatus(200);
    	if($error==1)
    	{
    		$datos = $data['mensaje'];
    		$datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

    	}
    	else
    	{
    		$datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
    	}
    	
    	$app->response->body(json_encode($datos));
	}); 

    $app->get('/getBuys/', function() use($app) 
    {
        // Tomando los datos
        $params    = $app->request->get();
        $data      = GetBuysByDate($params);
        $error     = $data['error'];
        $app->response->headers->set("Content-type","application/json");
        $app->response->setStatus(200);
        if($error==1)
        {
            $datos = $data['mensaje'];
            $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

        }
        else
        {
            $datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
        }
        
        $app->response->body(json_encode($datos));
    }); 

    $app->get('/deleteBuy/', function() use($app) 
    {   
            $params    = $app->request->get();
            $compra    = $params['compra'];
            $data      = DeleteBuy($compra);
            $error     = $data['error'];
           $app->response->headers->set("Content-type","application/json");
            $app->response->setStatus(200);
            if($error==1)
            {
                $datos = $data['mensaje'];
                $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

            }
            else
            {
                // Buscando los usuarios restantes.
                $buys      = GetBuysByDate($params);
                $error      = $buys['error'];
                if($error!=0)
                {
                    $info = "";
                }else{$info = $buys['info'];}
                $datos = array("info"=>$info,"error"=>$error ,"mensaje"=>$buys['mensaje']);
            }
            
            $app->response->body(json_encode($datos));
        
    }); 


    $app->post("/RegisterBuy/", function() use($app){
        $product = $app->request->post();
        // Registrando al producto
        $data   = RegisterBuy($product);
        $error          = $data['error'];
        $app->response->headers->set("Content-type","application/json");
        $app->response->setStatus(200);
        if($error==1)
        {
            $datos = $data['mensaje'];
            $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

        }
        else
        {
            $datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
        }
        
        $app->response->body(json_encode($datos));
    });

    $app->put("/EditBuy/", function() use($app){
        $product = $app->request->put();
        // Registrando al producto
        $data   = EditBuy($product);
       $error          = $data['error'];
        $app->response->headers->set("Content-type","application/json");
        $app->response->setStatus(200);
        if($error==1)
        {
            $datos = $data['mensaje'];
            $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

        }
        else
        {
            $datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
        }
        
        $app->response->body(json_encode($datos));
    });

     $app->get('/findBuyById/', function() use($app) 
    {   
            $params    = $app->request->get();
            $id_buy    = $params['id'];
            $data      = findBuyById($id_buy);
            $error     = $data['error'];
            $app->response->headers->set("Content-type","application/json");
            $app->response->setStatus(200);
            if($error==1)
            {
                $datos = $data['mensaje'];
                $datos = array("info"=>0,"error"=>1,"mensaje"=>$data["mensaje"]);

            }
            else
            {
              
                $datos = array("info"=>$data["info"],"error"=>0,"mensaje"=>"ok");
            }
            $app->response->body(json_encode($datos));
    }); 
 ?>