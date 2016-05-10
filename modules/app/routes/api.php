<?php 
	if(!defined("SPECIALCONSTANT")) die("acceso denegado");
	
	$app->get('/', function() use($app) 
	{
 	   $app->response->setStatus(200);
	    echo "Welcome to Slim 3.0 based API";
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




	
	$app->put("/books/",function() use($app){
		$id     = $app->request->put("id");
		$title  = $app->request->put("title");
		$isbn   = $app->request->put("isbn");
		$author = $app->request->put("author");
		try 
		{
			$con = getConnection();
			$dbh = $con->prepare("UPDATE books SET title = ?, isbn= ?, author = ?, created_at = NOW() WHERE id = ?");
			$dbh->bindParam(1,$title);
			$dbh->bindParam(2,$isbn);
			$dbh->bindParam(3,$author);
			$dbh->bindParam(4,$id);
			$dbh->execute();
 	    	$con = null;
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	$app->response->body(json_encode(array("res"=>1)));
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage();
		}
	});

	$app->delete("/books/:id",function ($id) use($app){
		
		try 
		{
			$con = getConnection();
			$dbh = $con->prepare("UDELETE FROM BOOKS WHERE id = ?");
			$dbh->bindParam(1,$id);
			$dbh->execute();
 	    	$con = null;
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	$app->response->body(json_encode(array("res"=>1)));
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage();
		}
	});
 ?>