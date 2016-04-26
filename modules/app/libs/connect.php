<?php 
	if(!defined("SPECIALCONSTANT")) die("acceso denegado");
	
	class ConexionBean
	{
		function _Con()
		{
			//$con = new PDO('pgsql:dbname=RaspBerry;host=localhost;user=postgres;password=12345');
			//return $con;
			require("rb.php");
			$con = R::setup('mysql:host=localhost;dbname=test','root', '' );
			return $con;
		}

		}//Conexion
	
	
 ?>