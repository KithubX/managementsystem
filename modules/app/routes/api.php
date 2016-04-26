<?php 
	if(!defined("SPECIALCONSTANT")) die("acceso denegado");
	
	$conexion   = new ConexionBean(); //Variable de conexión
	$con        = $conexion->_con(); //Variable de conexión

	$app->get('/', function() use($app) 
	{
 	   $app->response->setStatus(200);
	    echo "Welcome to Slim 3.0 based API";
	}); 

	$app->get('/users/', function() use($app) 
	{
 	    	$consultar  = new Consultar();
 	    	$libros     = $consultar->_ConsultarLibros();
 	    	$error      = $libros['error'];
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	if($error==1)
 	    	{
 	    		$datos = $libros['mensaje'];
 	    	}
 	    	else
 	    	{
 	    		$datos = array("users"=>$libros['info']);
 	    	}
 	    	
 	    	$app->response->body(json_encode($datos));
 	   	
	}); 


$app->get('/books/:id', function($id) use($app) 
	{
 	   try 
 	    {
 	    	$connection = getConnection();
 	    	$dbh        = $connection->prepare("select * from books where id = ?");
 	    	$dbh->bindParam(1,$id);
 	    	$dbh->execute();
 	    	$books      = $dbh->fetchObject();
 	    	$connection = null;

 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	$app->response->body(json_encode($books));
 	   	}
 	   catch (PDOException $e) 
		{
			echo "Error: ".$e->getMessage();	
		}
	}); 

	$app->post("/books/", function() use($app){
		$title  = $app->request->post("title");
		$isbn   = $app->request->post("isbn");
		$author = $app->request->post("author");

		try 
		{
			$con = getConnection();
			$dbh = $con->prepare("INSERT INTO books VALUES(NULL,?,?,?,NOW())");
			$dbh->bindParam(1,$title);
			$dbh->bindParam(2,$isbn);
			$dbh->bindParam(3,$author);
			$dbh->execute();
 	    	$bookId = $con->lastInsertId();
 	    	$con = null;
 	    	$app->response->headers->set("Content-type","application/json");
 	    	$app->response->setStatus(200);
 	    	$app->response->body(json_encode($bookId));
		} catch (PDOException $e) {
			echo "Error: ".$e->getMessage();
		}
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