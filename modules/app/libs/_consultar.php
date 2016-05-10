<?php 
	class Consultar
	{
		function _ConsultarUsuarios()
		{
			$query = '
				SELECT
				u.id,
				u.nb_user,
				u.nb_lname,
				u.nb_fname,
				r.nb_rol
				FROM users as u
				INNER JOIN roles r
				ON u.id_rol = r.id
				WHERE sn_activo = 1
			';
			$libros = $this->Ejecutarconsulta($query);
			return $libros;
		}//_ConsultarLibros

		function _ConsultarTipoUsuarios()
		{
			$query = '
				SELECT * FROM roles
			';
			$libros = $this->Ejecutarconsulta($query);
			return $libros;
		}

		function _GetUserById($id)
		{
			$query = '
				SELECT * FROM users where id = ?
			';
			$data = $this->EjecuteQueryOneParam($query,$id);
			return $data;
		}

		function EjecuteQueryOneParam($query,$param)
		{
			$error   = 0;
			$mensaje = "";
			$info    = "";
			R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query,[$param]);
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

		function Ejecutarconsulta($query)
		{
			$error   = 0;
			$mensaje = "";
			$info    = "";
			R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query);
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
	}//Consultar
 ?>