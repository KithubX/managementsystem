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
				u.de_email,
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

		
		// Funciones de Proveedores
		function _GetSuplierById($id)
		{
			$query = '
				SELECT * FROM proveedores where id = ?
			';
			$data = $this->EjecuteQueryOneParam($query,$id);
			return $data;
		}

		function _ConsultarProveedores()
		{
			$query = '
				SELECT * FROM proveedores WHERE sn_activo = 1
			';
			$proveedores = $this->Ejecutarconsulta($query);
			return $proveedores;
		}//_ConsultarLibros


		function _GetSuplierByName($name)
		{
			$query = 'SELECT * FROM proveedores WHERE nb_proveedor = ?';
			$data = $this->EjecuteQueryOneParam($query,$name);
			return $data;
		}

		// Funciones de productos
		function _GetProducts()
		{
			$query= '
				SELECT
				p.id,
				p.nb_producto,
				p.desc_producto,
				p.num_precio,
				pr.nb_proveedor

				FROM productos p
				INNER JOIN proveedores pr
				ON p.id_proveedor = pr.id
			';
			$productos = $this->Ejecutarconsulta($query);
			return $productos;
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

	}//Consultar
 ?>