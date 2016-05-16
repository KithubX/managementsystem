<?php 

	function GetSupliers()
	{
		$consultar = new Consultar();
		$proveedores  = $consultar->_ConsultarProveedores();
		return $proveedores;
	}

	function EliminarProveedor($id)
	{
		$model    = new SupliersModel();
		$suplier  = $model->DeleteSuplierById($id);
		return $suplier;
	}

	function GetSuplierByName($name)
	{
		$model    = new SupliersModel();
		$suplier  = $model->GetSuplierByName($name);
		return $suplier;
	}//GetSuplierByName

	function RegistrarProveedor($proveedor)
	{	
		$model    = new SupliersModel();
		$suplier  = $model->RegisterSuplier($proveedor);
		return $suplier;
	}

	function SuplierById($id)
	{
		$model    = new SupliersModel();
		$suplier  = $model->SuplierById($id);
		return $suplier;
	}

	function EditarProveedor($suplier)
	{
		$model    = new SupliersModel();
		$suplier  = $model->EditarProveedor($suplier);
		return $suplier;
	}

	function GetProducts()
	{
		$model    = new SupliersModel();
		$suplier  = $model->GetProducts();
		return $suplier;
	}

	function EliminarProducto($id)
	{
		$model    = new SupliersModel();
		$suplier  = $model->EliminarProducto();
		return $suplier;
	}

	function searchProductbyname($name,$proveedor)
	{
		$model    = new SupliersModel();
		$suplier  = $model->searchProductbyname($name,$proveedor);
		return $suplier;
	}

	function RegistrarProducto($product)
	{
		$model    = new SupliersModel();
		$suplier  = $model->registerProduct($product);
		return $suplier;
	}

	function ProductById($id)
	{
		$model    = new SupliersModel();
		$suplier  = $model->ProductById($id);
		return $suplier;
	}

	function EditarProducto($product)
	{
		$model    = new SupliersModel();
		$suplier  = $model->editProduct($product);
		return $suplier;
	}

	function GetBuysByDate($dates)
	{
		$model    = new SupliersModel();
		$suplier  = $model->GetBuysByDate($dates);
		return $suplier;
	}
 ?>