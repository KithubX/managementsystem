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
 ?>