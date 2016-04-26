<?php 
	session_start();
	include("libs.php");
	$conexion   = new ConexionBean(); //Variable de conexión
	$con        = $conexion->_con(); //Variable de conexión
	$Params=(isset($_GET['params']))?$_GET['Params']:$_POST['Params'];
	$Parametros=json_decode($Params,true);
	$Accion=$Parametros['Action'];

	switch ($Accion) {
		case 'CheckUser':
			$result = CheckUser($Parametros);
			echo json_encode($result);
		break;

	}
	

	function CheckUser($params)
	{
		$user   = $params['name'];
		$pass   = $params['password'];
		$query  = "SELECT * FROM users WHERE nb_user= ? AND pw_password = ?"; 
		$result = tranDoubleParam($query,$user,$pass);
		$amount = count($result['clientes']);
		if($amount>0)
		{
			$user 		      = $result['clientes'][0];
			$_SESSION["user"] = $user['nb_user'];
		}
		return $result;
	}


	function tranDoubleParam($query,$param,$param2)
		{
			$clientes = "";
			$error = false;
			$msj   = "";
			R::begin();
			    try{
			       $clientes = R::getAll($query,[$param,$param2]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $msj   = $e->getMessage();
			       $error = true;
			       $clientes =  R::rollback();
			    }
			R::close();
			$datos = array("clientes"=>$clientes,"error"=>$error,"msj"=>$msj);
			return $datos;
		}

 ?>