<?php 
	class CurlRequest
	{
		public function sendNothing()
		{
			$data = "";
			$ch = curl_init("http://localhost/slimrest/index.php/");
			//a true, obtendremos una respuesta de la url, en otro caso, 
			//true si es correcto, false si no lo es
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//establecemos el verbo http que queremos utilizar para la petición
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			//enviamos el array data
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			//obtenemos la respuesta
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}

		public function sendPost()
		{
			//datos a enviar
			$data = array("title" => "un libro","isbn"=>"998-84-8181-8","author"=>"un autor :)");
			//url contra la que atacamos
			$ch = curl_init("http://localhost/slimrest/index.php/books");
			//a true, obtendremos una respuesta de la url, en otro caso, 
			//true si es correcto, false si no lo es
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//establecemos el verbo http que queremos utilizar para la petición
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			//enviamos el array data
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			//obtenemos la respuesta
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}//sendPost
	 
		public function sendPut()
		{
			//datos a enviar
			$data = array("a" => "a");
			//url contra la que atacamos
			$ch = curl_init("http://localhost/curlRequest/api.php");
			//a true, obtendremos una respuesta de la url, en otro caso, 
			//true si es correcto, false si no lo es
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//establecemos el verbo http que queremos utilizar para la petición
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
			//enviamos el array data
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			//obtenemos la respuesta
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}//sendPut
	 
		public function sendGet()
		{
			//datos a enviar
			$data = array("a" => "a");
			//url contra la que atacamos
			$ch = curl_init("http://localhost/curlRequest/api.php");
			//a true, obtendremos una respuesta de la url, en otro caso, 
			//true si es correcto, false si no lo es
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//establecemos el verbo http que queremos utilizar para la petición
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			//enviamos el array data
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			//obtenemos la respuesta
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}//sendGet

		public function sendgetAll()
		{
			$ch = curl_init("http://localhost/slimbean/index.php/books");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}
	 
		public function sendDelete()
		{
			//datos a enviar
			$data = array("a" => "a");
			//url contra la que atacamos
			$ch = curl_init("http://localhost/curlRequest/api.php");
			//a true, obtendremos una respuesta de la url, en otro caso, 
			//true si es correcto, false si no lo es
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//establecemos el verbo http que queremos utilizar para la petición
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
			//enviamos el array data
			curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
			//obtenemos la respuesta
			$response = curl_exec($ch);
			// Se cierra el recurso CURL y se liberan los recursos del sistema
			curl_close($ch);
			if(!$response) {
			    return false;
			}else{
				var_dump($response);
			}
		}//sendDelete
	}

	$curl = new CurlRequest();
	$curl->sendgetAll();
 ?>