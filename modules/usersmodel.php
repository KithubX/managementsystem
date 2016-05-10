<?php 
	class UsersModel
	{
		function GetUsersModel()
		{
			$consultar = new Consultar();
			$usuarios  = $consultar->_ConsultarUsuarios();
			return $usuarios;
		}	


		function GetTypeUsers()
		{
			$consultar  = new Consultar();
			$tiposUser  = $consultar->_ConsultarTipoUsuarios();
			return $tiposUser;
		}

		function GetUserByUserName($user)
		{
			$User   = R::findOne( 'users', ' nb_user = ?', [$user]);
			return $User;
		}

		function _RegisterUser($user)
		{
			// Creando el objeto de la tabla y asignadno las propiedades
			$usuario = R::dispense("users");
			$usuario->nb_user 	  = $user['nb_user'];
			$usuario->pw_password = $user['password'];
			$usuario->nb_lname 	  = $user['nb_lname'];
			$usuario->nb_fname 	  = $user['nb_fname'];
			$usuario->de_email	  = $user['de_email'];			
			$usuario->id_rol	  = $user['type'];	
			$usuario->sn_activo	  = 1;	
			// almacenando el usuario
			$datos = $this->StoreObject($usuario);
			return $datos;
		}

		function _EditUser($user)
		{
			$userLoad = R::load("users",$user['user_id']);
			
			// Editando las propiedades
			$userLoad->nb_user  = $user['nb_user'];
			$userLoad->nb_lname = $user['nb_lname'];
			$userLoad->nb_fname = $user['nb_fname'];
			$userLoad->de_email = $user['de_email'];
			$userLoad->id_rol   = $user['type'];
			// almacenando el usuario
			$datos = $this->StoreObject($userLoad);
			return $datos;
		}

		function _UserById($id)
		{
			$consultar = new Consultar();
			$user      = $consultar->_GetUserById($id);
			return $user;
		}

		function _deleteUserById($id)
		{
			$userLoad = R::load("users",$id);
			
			// Editando las propiedades
			$userLoad->sn_activo = 0;
			// almacenando el usuario
			$datos = $this->StoreObject($userLoad);
			return $datos;
		}

		function StoreObject($object)
		{
			$error   = 0;
			$mensaje = "";
			$info    = "";
			R::freeze(1);
			R::begin();
			    try{
			       $respuesta = R::store($object);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info    =  R::rollback();
			       $error   = 1;
			       $mensaje = $e->getMessage();
			    }
			R::close();
			$datos = array("info"=>$info,"error"=>$error,"mensaje"=>$mensaje);
			return $datos;
		}
	}

 ?>