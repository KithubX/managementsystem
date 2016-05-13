<?php 

	class SupliersModel
	{
		function GetSupliersModel()
		{
			$proveedores = new Consultar();
			$usuarios  = $proveedores->_ConsultarProveedores();
			return $proveedores;
		}

		function DeleteSuplierById($id)
		{
			$userModel = new UsersModel();

			$suplierload = R::load("proveedores",$id);
			
			// Editando las propiedades
			$suplierload->sn_activo = 0;
			// almacenando el usuario
			$datos = $userModel->StoreObject($suplierload);
			return $datos;
		}

		function GetSuplierByName($name)
		{
			$consultar = new Consultar();
			$proveedor = $consultar->_GetSuplierByName($name);
			return $proveedor;
		}

		function RegisterSuplier($proveedor)
		{
			$userModel = new UsersModel();
			// Creando el objeto de la tabla y asignando las propiedades
			$suplier = R::dispense("proveedores");
			$suplier->nb_proveedor   = $proveedor['nb_proveedor'];
			$suplier->desc_proveedor = $proveedor['desc_proveedor'];
			$suplier->desc_address   = $proveedor['desc_address'];
			$suplier->num_telefono   = $proveedor['num_telefono'];
			$suplier->sn_activo      = 1;
			$datos = $userModel->StoreObject($suplier);
			return $datos;
		}

		function SuplierById($id)
		{
			$consultar = new Consultar();
			$user      = $consultar->_GetSuplierById($id);
			return $user;
		}

		function EditarProveedor($proveedor)
		{
			$userModel = new UsersModel();
			// Creando el objeto de la tabla y asignando las propiedades
			$suplier = R::load("proveedores",$proveedor['suplier_id']);
			$suplier->nb_proveedor   = $proveedor['nb_proveedor'];
			$suplier->desc_proveedor = $proveedor['desc_proveedor'];
			$suplier->desc_address   = $proveedor['desc_address'];
			$suplier->num_telefono   = $proveedor['num_telefono'];
			$suplier->sn_activo      = 1;
			$datos = $userModel->StoreObject($suplier);
			return $datos;
		}

		function GetProducts()
		{
			$consultar = new Consultar();
			$products  = $consultar->_GetProducts();
			return $products;	
		}

		function EliminarProducto($id)
		{
			$userModel = new UsersModel();

			$Productload = R::load("products",$id);
			
			// Editando las propiedades
			$Productload->sn_activo = 0;
			// almacenando el usuario
			$datos = $userModel->StoreObject($Productload);
			return $datos;
		}

		function searchProductbyname($name,$proveedor)
		{
			$consultar = new Consultar();
			$proveedor = $consultar->_GetProductByName($name,$proveedor);
			return $proveedor;
		}

		function registerProduct($product)
		{
			$userModel = new UsersModel();
			// Creando el objeto de la tabla y asignando las propiedades
			$suplier = R::dispense("productos");
			$suplier->nb_producto    = $product['nb_producto'];
			$suplier->desc_producto  = $product['desc_producto'];
			$suplier->num_precio     = $product['num_precio'];
			$suplier->id_proveedor   = $product['id_proveedor'];
			$datos = $userModel->StoreObject($suplier);
			return $datos;
		}
	}
	

 ?>