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

		function _GetProductByName($name,$proveedor)
		{
			$query = 'SELECT * FROM productos WHERE nb_producto = ? AND id_proveedor = ?';
			$data = $this->EjecuteQueryTwoParam($query,$name,$proveedor);
			return $data;
		}

		function _ProductById($id)
		{
			$query = '
				SELECT * FROM productos where id = ?
			';
			$data = $this->EjecuteQueryOneParam($query,$id);
			return $data;
		}

		function _getBuysByDate($dates)
		{
			$start = $dates['dateStart'];
			$end   = $dates['dateEnd'];
			$query = "
				select  
				com.id,
				com.id_producto,
				com.id_proveedor,
				com.num_cantidad,
				com.num_total,
				DATE(com.fec_compra) as fec_compra,
				prov.nb_proveedor,
				prod.desc_producto,
				prod.num_precio,
				prod.nb_producto
				from compras com
				inner join proveedores prov
				ON prov.id = com.id_proveedor
				INNER JOIN productos prod
				ON prod.id = com.id_producto
				where com.fec_compra >= ? and com.fec_compra <= ?
				AND com.sn_activo = 1
				ORDER BY com.fec_compra ASC
			";
			$data = $this->EjecuteQueryTwoParam($query,$start,$end);
			return $data;

		}

		function _getProductsBySuplier($id)
		{
			$query = "
				select id,nb_producto,num_precio from productos where id_proveedor = ? and sn_activo = 1
			";
			$data = $this->EjecuteQueryOneParam($query,$id);
			return $data;
		}

		function _findBuyById($id)
		{
			$query = '
				select
				id,
				id_producto,
				id_proveedor,
				num_cantidad,
				num_total,
				desc_compra,
				DATE_FORMAT(fec_compra,"%m-%d-%Y") as fec_compra
				from compras
				where id = ?
			';
			$data = $this->EjecuteQueryOneParam($query,$id);
			return $data;
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

		function EjecuteQueryTwoParam($query,$param,$param2)
		{
			$error   = 0;
			$mensaje = "";
			$info    = "";
			R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query,[$param,$param2]);
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