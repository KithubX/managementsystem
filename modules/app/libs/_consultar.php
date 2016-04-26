<?php 
	class Consultar
	{
		function _ConsultarLibros()
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
			';
			$libros = $this->Ejecutarconsulta($query);
			return $libros;
		}//_ConsultarLibros

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