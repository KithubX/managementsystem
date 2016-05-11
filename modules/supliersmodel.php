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
	}
	

 ?>