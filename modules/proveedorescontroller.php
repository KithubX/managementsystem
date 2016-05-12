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
 ?>