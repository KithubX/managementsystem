<?php 
	$conexion   = new ConexionBean(); //Variable de conexión
	$con        = $conexion->_con(); //Variable de conexión
	
	
	function CheckUser($user,$pass)
	{
		$query  = "SELECT * FROM users WHERE nb_user= ? AND pw_password = ?"; 
		$result = tranDoubleParam($query,$user,$pass);
		$amount = count($result['clientes']);
		if($amount>0)
		{
			$user 		         = $result['clientes'][0];
			$_SESSION["user"]    = $user['nb_user'];
			$_SESSION["id_user"] = $user['id'];
		}
		return $result;
	}

	function GetUsers()
	{
		$Model = new UsersModel();
		$users = $Model->GetUsersModel();
		return $users;
	}


	function GetTypeUsers()
	{
		$model = new UsersModel();
		$types = $model->GetTypeUsers();
		return $types;
	}

	function buscarExistenciaPorUserName($user)
	{
		$model = new UsersModel();
		$types = $model->GetUserByUserName($user);
		return $types;
	}

	function EditarUsuario($user)
	{
		$model      = new UsersModel();
		$Registered = $model->_EditUser($user);
		return $Registered;
	}

	function RegistrarUsuario($user)
	{
		$model      = new UsersModel();
		$Registered = $model->_RegisterUser($user);
		return $Registered;
	}

	function UserById($id)
	{
		$model = new UsersModel();
		$user  = $model->_UserById($id);
		return $user;
	}

	function EliminarUsuario($user)
	{
		$model = new UsersModel();
		$user  = $model->_deleteUserById($user);
		return $user;
	}

	function tranDoubleParam($query,$param,$param2)
		{
			$clientes = "";
			$error   = 0;
			$msj = "";
			$info    = "";
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