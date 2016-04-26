<?php
	session_start();
	include("../../libs/libs.php");
	$conexion   = new ConexionBean(); //Variable de conexión
	$con        = $conexion->_con(); //Variable de conexión
	$Params=(isset($_GET['Params']))?$_GET['Params']:$_POST['Params'];
	$Parametros=json_decode($Params,true);
	$Accion=$Parametros['Accion'];

	//Switch de las funciones
	switch($Accion)
	{
		case 'EjecutarTest':
			$salidaJson = EjecutarTest($Parametros);
			echo json_encode($salidaJson);
		break;

		case 'ResultadosBiotest':
			$salidaJson = ResultadosBiotest($Parametros);
			echo json_encode($salidaJson);
		break;

		case 'UltimoBiotestCliente':
			$salidaJson = UltimoBiotestCliente($Parametros);
			echo json_encode($salidaJson);
		break;

		case 'ObtenerRutinaPorCliente':
			$salidaJson = ObtenerRutinaPorCliente($Parametros);
			echo json_encode($salidaJson);
		break;

		case 'EnviarResultados':
			$salidaJson = EnviarResultados($Parametros);
			echo json_encode($salidaJson);
		break;

		//Casos viejos

		case 'CondicionFisica':
			$id_Cliente		   = $Parametros['Id_Cliente'];
			$ResultadoEvaluado = $Parametros['ResultadoEvaluado'];
			$id_instructor	   = $Parametros['id_instructor'];
			$salidaJson		   = CondicionFisica($id_Cliente,$ResultadoEvaluado,$id_instructor);
			echo json_encode($salidaJson);
		break;
		
		case 'Peso':
			$id_Cliente		= $Parametros['Id_Cliente'];
			$Altura			= $Parametros['Altura'];
			$peso			= $Parametros['peso'];
			$id_instructor	= $Parametros['id_instructor'];
			$salidaJson		= Peso($id_Cliente,$Altura,$id_instructor,$peso);
			echo json_encode($salidaJson);
		break;
		
		case 'IMC':
			$id_Cliente	   = $Parametros['Id_Cliente'];
			$Altura		   = $Parametros['Altura'];
			$peso		   = $Parametros['peso'];
			$id_instructor = $Parametros['id_instructor'];
			$salidaJson	   = IMC($id_Cliente,$Altura,$id_instructor,$peso);
			echo json_encode($salidaJson);
		break;
		
		case 'IMM':
			$id_Cliente=$Parametros['Id_Cliente'];
			//Datos de la prueba
			$Cintura					= $Parametros['Cintura'];
			$Cadera						= $Parametros['Cadera'];
			$Perimetro_Espalda			= $Parametros['Perimetro_Espalda'];
			$Perimetro_Pecho			= $Parametros['Perimetro_Pecho'];
			$Perimetro_brazo_relajado	= $Parametros['Perimetro_brazo_relajado'];
			$Perimetro_brazo_flexionado = $Parametros['Perimetro_brazo_flexionado'];
			$Perimetro_femoral			= $Parametros['Perimetro_femoral'];
			$Perimetro_Pantorrilla		= $Parametros['Perimetro_Pantorrilla'];
			//Fin datos prueba//
			$id_instructor				= $Parametros['id_instructor'];
			$salidaJson = IMM($id_Cliente,$id_instructor,$Cintura, $Cadera,$Perimetro_Espalda, $Perimetro_Pecho,$Perimetro_brazo_relajado, 
			$Perimetro_brazo_flexionado,$Perimetro_femoral,$Perimetro_Pantorrilla );
			echo json_encode($salidaJson);
		break;
		
		case 'Stamina':
			$id_Cliente	   = $Parametros['Id_Cliente'];
			$id_instructor = $Parametros['id_instructor'];
			$repeticiones  = $Parametros['repeticiones'];
			$salidaJson	   = Stamina($id_Cliente,$repeticiones, $id_instructor);
			echo json_encode($salidaJson);
		break;
		
		case 'Fuerza':
			$id_Cliente	   = $Parametros['Id_Cliente'];
			$id_instructor = $Parametros['id_instructor'];
			$repeticiones  = $Parametros['repeticiones'];
			$salidaJson	   = Fuerza($id_Cliente,$repeticiones, $id_instructor);
			echo json_encode($salidaJson);
		break;
		
		case 'Resistencia':
			$id_Cliente	   = $Parametros['Id_Cliente'];
			$id_instructor = $Parametros['id_instructor'];
			$repeticiones  = $Parametros['repeticiones'];
			$salidaJson	   = Resistencia($id_Cliente,$repeticiones, $id_instructor);
			echo json_encode($salidaJson);
		break;
		
		case 'Flexibilidad':
			$id_Cliente    = $Parametros['Id_Cliente'];
			$id_instructor = $Parametros['id_instructor'];
			$Flexibilidad  = $Parametros['Flexibilidad'];
			$salidaJson=Flexibilidad($id_Cliente,$Flexibilidad, $id_instructor);
			echo json_encode($salidaJson);
		break;
		
		case 'ReportePdf':
			$id_Cliente =  $Parametros['Id_Cliente'];
			$salidaJson = ReportePdf($id_Cliente);
			echo json_encode($salidaJson);
		break;
		
		case 'CrearCapturaImagen':
		$Params=$_POST['Params'];
		$Parametros=json_decode($Params,true);
		$de_imagen=$Parametros['de_imagen'];
		
		//Creando la carpeta para el cliente
		if($Parametros['Prueba'] == 'CondicionFisica')
		{
			$Id_Cliente  = $Parametros['Id_Cliente'];	
			$dirPath 	 = $_SERVER['DOCUMENT_ROOT']."/Spinpgym/modulos/Biotest/TestPictures".$Id_Cliente;	
			$result 	 = mkdir($dirPath, 0755);	
			$folder_name = "TestPictures".$Id_Cliente;
		}
		else { $folder_name = $Parametros['Folder_Name'];}
		
		
		//Get the base-64 string from data
		$filteredData=substr($Parametros['de_imagen'], strpos($Parametros['de_imagen'], ",")+1);
		
		//Decode the string
		$unencodedData=base64_decode($filteredData);
		$fileName=$Parametros['Id_Cliente'].$Parametros['Prueba'];
		file_put_contents($folder_name."/".$fileName.".jpg", $unencodedData);
		
		//Definiendo el nombre de la variable que se dará, este se otorga a partir del tipo de prueba.
		switch($Parametros['Prueba'])
		{
			case 'CondicionFisica':
				$salidaJson = array("CondicionFisicaVar"=>$fileName.".jpg", "Folder_Name"=>$folder_name);
			break;
			
			case 'Peso':
				$salidaJson = array("PesoVar"=>$fileName.".jpg");
			break;
			
			case 'IMC':
				$salidaJson = array("IMCVar"=>$fileName.".jpg");
			break;
			
			case 'IMM':
				$salidaJson = array("IMMVar"=>$fileName.".jpg");
			break;
			
			case 'Stamina':
				$salidaJson = array("StaminaVar"=>$fileName.".jpg");
			break;
			
			case 'Fuerza':
				$salidaJson = array("FuerzaVar"=>$fileName.".jpg");
			break;
			
			case 'Resistencia':
				$salidaJson = array("ResistenciaVar"=>$fileName.".jpg");
			break;
			
			case 'Flexibilidad':
				$salidaJson = array("FlexibilidadVar"=>$fileName.".jpg");
			break;
			
			
		}//switch
		echo json_encode($salidaJson);
		
		break;
	}//switch de opciones
	
	///Funciones de condición física///

	function EjecutarTest($Parametros)
	{
		//Tomando los datos.
		$peso    = $Parametros['peso'];
		$altura  = $Parametros['altura'];
		$cliente = $Parametros['cliente'];
		$espalda = $Parametros['espalda'];
		$pecho   = $Parametros['pecho'];
		$abdomen = $Parametros['abdomen'];
		$cadera  = $Parametros['cadera'];
		$brazo   = $Parametros['brazo'];
		$muslo   = $Parametros['muslo'];

		//Obteniendo los valores a ver si están fuera de rango.
		$diagnosticoimc  = IMCResultado($peso,$altura);
		$diagnosticopeso = RangoDePesoPorAltura($altura, $peso);
		$exitoimc        = 0;
		$exitoPeso       = 0;
		$imcDatos 	     = "";
		$pesoDatos 		 = "";

		if($diagnosticoimc!="Fuera de rango" && $diagnosticopeso!="Fuera de rango")
		{
			date_default_timezone_set("America/Chihuahua");
			$fh_creacion  = date("Y-m-d"); //fecha del día de hoy
			$id_inst      = $_SESSION['usuario']['id'];
			//si entra aquí es por que los datos ingresados son correctos.
			$imcDatos     = IMCLight($peso,$altura,$cliente,$fh_creacion,$id_inst);
			$pesoDatos    = PesoLight($peso,$altura,$cliente,$fh_creacion,$id_inst);
			$exitoimc     = 1;
			$exitoPeso    = 1;

			//Tomando el id de l aprueba IMM
			//Guardando el resultado.
			$Prueba       = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Imm' ] );
			$id_pruebaimm = $Prueba->id;
			
			$Diagnostico  = "No Aplica";
			$desc_espalda = "IMM - Espalda";
			$desc_pecho   = "IMM - Pecho";
			$desc_abd     = "IMM - Abdomen";
			$desc_cad     = "IMM - Cadera";
			$desc_bra     = "IMM - Brazo";
			$desc_mus     = "IMM - Muslo";

			// Guardando los resultados light
			
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_espalda,$Diagnostico,0,$fh_creacion,$espalda);
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_pecho,$Diagnostico,0,$fh_creacion,$pecho);
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_abd,$Diagnostico,0,$fh_creacion,$abdomen);
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_cad,$Diagnostico,0,$fh_creacion,$cadera);
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_bra,$Diagnostico,0,$fh_creacion,$brazo);
			$respuesta    = GuardarResultadoPruebasLight($id_inst,$cliente,$id_pruebaimm,$desc_mus,$Diagnostico,0,$fh_creacion,$muslo);
			
			// Guardando los resultados del biotest ultra.
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_espalda,$Diagnostico,0,$fh_creacion,$espalda);
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_pecho,$Diagnostico,0,$fh_creacion,$pecho);
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_abd,$Diagnostico,0,$fh_creacion,$abdomen);
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_cad,$Diagnostico,0,$fh_creacion,$cadera);
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_bra,$Diagnostico,0,$fh_creacion,$brazo);
			$respuesta    = GuardarResultadoPruebas($id_inst,$cliente,$id_pruebaimm,$desc_mus,$Diagnostico,0,$fh_creacion,$muslo);

		}//if
		else
		{
			//si se entra aquí es por que alguno de los elementos está fuera del rango de valores
			$exitoPeso = ($diagnosticopeso!="Fuera de rango")?1:0;
			$exitoimc  = ($diagnosticoimc!="Fuera de rango")?1:0;
		}//else
		
		$datos = array("imc"=>$imcDatos,"peso"=>$pesoDatos,"exitoimc"=>$exitoimc,"exitoPeso"=>$exitoPeso);
		return $datos;
	}//EjecutarTest

	function ResultadosBiotest($Parametros)
	{
		//Tomando los valores
		$cliente       = $Parametros['id'];
		$consultar     = new Consultar();
		//Se deben buscar los resultados de toda slas pruebas.
		// Resuiltados de peso.
		$Prueba        = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Peso' ] );
		$id_prueba     = $Prueba->id;
		$resultadoPeso = $consultar->_ConsultarResultadosPruebaslight($id_prueba,$cliente);
		$cantidadpeso  = count($resultadoPeso);
		$exitopeso     = ($cantidadpeso>0)?1:0;
		$datospeso     = $consultar->_ConsultarResultadoPruebaCliente($cliente,$id_prueba);
		$resultpeso    = $datospeso['resultado'];
		$Prueba        = R::findOne( 'sgconsejos', ' id_tipo_prueba = ?  and Resultado = ?', [$id_prueba,$resultpeso] );
		$consejoPeso   = $Prueba['consejo'];
		// Resuiltados de IMC.
		$Prueba        = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Imc' ] );
		$id_pruebaimc  = $Prueba->id;
		$resultadoImc  = $consultar->_ConsultarResultadosPruebaslight($id_pruebaimc,$cliente);
		$cantidadimc   = count($resultadoImc);
		$exitoimc      = ($cantidadimc>0)?1:0;
		$datosimc      = $consultar->_ConsultarResultadoPruebaCliente($cliente,$id_pruebaimc);
		$resulimc      = $datosimc['resultado'];
		$Prueba        = R::findOne( 'sgconsejos', ' id_tipo_prueba = ?  and Resultado = ?', [$id_pruebaimc,$resulimc] );
		$consejoimc    = $Prueba['consejo'];
		// Resuiltados de IMM.
		$Prueba        = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Imm' ] );
		$id_prueba     = $Prueba->id;
		$resultadoImm  = $consultar->_ConsultarResultadosPruebasIMM($id_prueba,$cliente);
		$cantidadimm   = count($resultadoImm);
		$exitoimm      = ($cantidadimm>0)?1:0;

		//Resultados de los consejos.
		

		//Verificando la cantidad de resultados para autocompletar.
		if($cantidadpeso<3)
		{
			for($i=0; $i<=$cantidadpeso; $i++)
			{
				$datopeso = array("desc_prueba"=>0,"fh_creacion"=>0,"id"=>0,"id_cliente"=>$cliente,
								  "id_instructor"=>0,"porcentaje"=>0,"resultado"=>0,
								  "resultado_numerico"=>0,"tipo_prueba"=>0);
				array_push($resultadoPeso,$datopeso);
			}//for
		}//if

		if($cantidadimc<3)
		{
			for($i=0; $i<=$cantidadimm; $i++)
			{
				$datopeso = array("desc_prueba"=>0,"fh_creacion"=>0,"id"=>0,"id_cliente"=>$cliente,
								  "id_instructor"=>0,"porcentaje"=>0,"resultado"=>0,
								  "resultado_numerico"=>0,"tipo_prueba"=>0);
				array_push($resultadoImc,$datopeso);
			}//for
		}//if

		// Verificando la cantidad de reusltados de IMM
		// se debe agrupar por fechas.
		$immActuales   = array();
		$immAnteriores = array();

		if($cantidadimm<12)
		{
			$immActuales = $resultadoImm;

			//Haciendo un ciclo para insertar los datos falsos.
			for($i=0; $i<6; $i++)
			{
				$datoimm = array("desc_prueba"=>0,"fh_creacion"=>0,"id"=>0,"id_cliente"=>0,
								 "id_instructor"=>0,"porcentaje"=>0,"resultado"=>0,
								 "resultado_numerico"=>0,"tipo_prueba"=>0);
				array_push($immAnteriores,$datoimm);
			}//for


		}//if
		else
		{
			// Si entra aquí es por que tiene resultads viejos.
			$fechaprueba = $resultadoImm[0]['fh_creacion'];
			for($i=0; $i<12; $i++)
			{
				$fechaimm = $resultadoImm[$i]['fh_creacion'];
				if($fechaprueba==$fechaimm)
				{
					array_push($immActuales,$resultadoImm[$i]);
				}else{array_push($immAnteriores,$resultadoImm[$i]);}
			}//for
		}//else

		//Devolviendo los datos
		$datos =  array("exitoPeso"=>$exitopeso,"exitoimc"=>$exitoimc,"exitoimm"=>$exitoimm,
						"peso"=>$resultadoPeso,"imc"=>$resultadoImc,"imm"=>$immActuales,
						"immAnt"=>$immAnteriores,"consejoPeso"=>$consejoPeso,"consejoimc"=>$consejoimc);
		return $datos;
	}//ResultadosBiotest

	function UltimoBiotestCliente($Parametros)	
	{
		$cliente   = $Parametros['id'];
		$consultar = new Consultar();
		$bioresult = $consultar->_ConsultarFechaUltimoBiotestRealizado($cliente);
		$fecha     = $bioresult['Ultimo_Biotest'];
		$permiso   = 0;
		$Dias_trans = 0;
		if ($fecha !="")
		{
			date_default_timezone_set("America/Chihuahua");
			$Fecha_Actual  = date("Y-m-d"); //fecha del día de hoy.
			//Si trae una fecha se verifica cuantos días han transcurrido.
			$fechas		   = explode(" ",$fecha);
			$fechaSinHoras = $fechas[0];
			$Utilidades	   = new Utilidades();
			$resdias       = $Utilidades->udate($Fecha_Actual,$fechaSinHoras);
			$Dias_trans    = ($resdias['Dias_Transcurridos']!="")?$resdias['Dias_Transcurridos']:0;
			$permiso       = ($Dias_trans >=15)?1:0; //Silas fechas osn idénticas, no hay permiso
			
		}//if
		else
		{
			$permiso = true; 
		}//else

		$datos = array("permiso"=>$permiso,"Dias_trans"=>$Dias_trans);
		return $datos;
	}//UltimoBiotestCliente

	function ObtenerRutinaPorCliente($Parametros)
	{
		$cliente = $Parametros['id'];
		//Obteniendo el número de rutina por cliente.
		$consultar = new Consultar();
		$resultrut = $consultar->_ConsultarRutinasClientesPorIdCliente($cliente);
		$id_rutina = $resultrut['id'];
		$datos     = array("rutina"=>$id_rutina);
		return $datos;
	}//ObtenerRutinaPorCliente

	function EnviarResultados($Parametros)
	{
		$cliente = $Parametros['id'];
		
	}//ResultadosBiotest

	function IMCResultado ($peso,$altura)
	{
		//Sacando el índice de masa corporal
		$alturaMetros   = $altura/100; //convirtiéndo la altura en metros
		$alturaCuadrada = $alturaMetros*$alturaMetros;
		$imc			= number_format($peso/$alturaCuadrada,2); //Dejando el númnero con solo 2 decimales	
		$Diagnostico	= DiagnosticoAcordeResultadoIMC($imc);
		return $Diagnostico;
	}//IMCResultado

	function IMCOperacion($peso,$altura)
	{
		$alturaMetros   = $altura/100; //convirtiéndo la altura en metros
		$alturaCuadrada = $alturaMetros*$alturaMetros;
		$imc			= number_format($peso/$alturaCuadrada,2); //Dejando el númnero con solo 2 decimales	
		return $imc;
	}//IMCOperacion

	function IMCLight($peso,$altura,$cliente,$fh_creacion,$id_inst)
	{
		$imc 				 = IMCOperacion($peso,$altura);
		$Diagnostico	     = DiagnosticoAcordeResultadoIMC($imc);
		$porcentajeResultado = "";
		$consultar        	 = new Consultar();
		$resultadoPruebas 	 = "";

		//Verificando si está dentro del rango 
		if($Diagnostico != "Fuera de rango")
		{
			//Tomar la fecha de hoy
			$porcentaje  = PorcentajeAcordeResultadoPeso($Diagnostico);

			//Guardando el resultado.
			$Prueba      = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Imc' ] );
			$id_prueba   = $Prueba->id;
			$desc_prueba = "IMC";
			$respuesta   = GuardarResultadoPruebas($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$imc);
			$respuesta   = GuardarResultadoPruebasLight($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$imc);

			//Tomando los resultados del cliente de esa prueba.
			$resultadoPruebas=$consultar->_ConsultarResultadosPruebaslight($id_prueba,$cliente);
			
		}//else
		$datosimc    = array("resultado_numerico"=>$imc,"Diagnostico"=>$Diagnostico,"resultados"=>$resultadoPruebas);
		return $datosimc;
	}//IMC

	function PesoLight($peso,$altura,$cliente,$fh_creacion,$id_inst)
	{
		$Diagnostico = RangoDePesoPorAltura($altura, $peso);
		$porcentaje  = "";
		$consultar   = new Consultar();

		//haciendo la verificación de errores
		if($Diagnostico!="Fuera de rango")
		{
			$porcentaje  = PorcentajeAcordeResultadoPeso($Diagnostico);	
			$Prueba      = R::findOne( 'sgtipospruebas', ' nm_prueba = ? ', [ 'Peso' ] );
			$id_prueba   = $Prueba->id;
			$desc_prueba = "Peso";
			$respuesta   = GuardarResultadoPruebas($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$peso);
			$respuesta   = GuardarResultadoPruebasLight($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$peso);

			//Tomando los resultados del cliente de esa prueba.
			$resultadoPruebas=$consultar->_ConsultarResultadosPruebaslight($id_prueba,$cliente);
		}//else
		$datosimc    = array("resultado_numerico"=>$peso,"Diagnostico"=>$Diagnostico,"resultados"=>$resultadoPruebas);
		return $datosimc;
	}//Peso

	function GuardarResultadoPruebas($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$imc)
	{
		$pruebastore 					 = R::dispense("sgpruebas");
		$pruebastore->id_instructor      = $id_inst;
		$pruebastore->id_cliente         = $cliente;
		$pruebastore->tipo_prueba        = $id_prueba;
		$pruebastore->desc_prueba        = $desc_prueba;
		$pruebastore->resultado_numerico = $imc;
		$pruebastore->resultado          = $Diagnostico;
		$pruebastore->porcentaje         = $porcentaje;
		$pruebastore->fecha  		     = $fh_creacion;
		$respuesta = EjecutarTransaccion($pruebastore);
		return $respuesta;
	}//GuardarResultadoPruebas

	function GuardarResultadoPruebasLight($id_inst,$cliente,$id_prueba,$desc_prueba,$Diagnostico,$porcentaje,$fh_creacion,$imc)
	{
		$pruebastore 					 = R::dispense("sgpruebaslight");
		$pruebastore->id_instructor      = $id_inst;
		$pruebastore->id_cliente         = $cliente;
		$pruebastore->tipo_prueba        = $id_prueba;
		$pruebastore->desc_prueba        = $desc_prueba;
		$pruebastore->resultado_numerico = $imc;
		$pruebastore->resultado          = $Diagnostico;
		$pruebastore->porcentaje         = $porcentaje;
		$pruebastore->fh_creacion        = $fh_creacion;
		$respuesta = EjecutarTransaccion($pruebastore);
		return $respuesta;
	}//GuardarResultadoPruebas

	function EjecutarTransaccion($objeto)
	{
		R::freeze(1);
		R::begin();
		    try{
		       $respuesta = R::store($objeto);
		        R::commit();
		    }
		    catch(Exception $e) {
		       $respuesta =  R::rollback();
		       $respuesta = "Error";
		    }
		R::close();
		return $respuesta;
	}//EjecutarTransacción

	//Funciones viejas
	/////Funciones de medida de peso //////
	function Peso1($id_Cliente,$Altura,$id_instructor,$peso)
	{
		$agregar     = new Agregar();
		$consultar   = new Consultar();
		//Tomando los resultados acorde a los datos enviados
		$RangoDePeso = RangoDePesoPorAltura($Altura, $peso);
		
		//haciendo la verificación de errores
		if($RangoDePeso=="Fuera de rango")
		{
			$salidaJson = array("Condicion"=>$RangoDePeso, "Altura"=>$Altura, "id_cliente"=>$id_Cliente,"peso"=>$peso, "TipoPrueba"=>2);
		}
		else{
		$PorcentajeAcordeResultado = PorcentajeAcordeResultadoPeso($RangoDePeso);	
		//Agregando el resultado en la BD
		$TipoPrueba = 2;
		$DescPrueba = "Peso";
		//Guardando los resultados de la prueba
		$resAgregar = $agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $RangoDePeso, $PorcentajeAcordeResultado,$peso);
		
		//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
		$resultadoPruebas = $consultar->_ConsultarResultadosPruebas(2,$id_Cliente);
		
		//Creando un array con los resultados de las pruebas
			$arrayResultados=array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje=($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado=$fila['Resultado'];
				$Prueba=array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}//for
			
			//Tomando el consejo de la evaluación física
			$resultConsejo=$consultar->_ConsultarConsejoAcordeResultado(2,$RangoDePeso);
			$filaConsejo=$resultConsejo->fetch_assoc();
			$consejo=$filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson=array("Condicion"=>$RangoDePeso, "Porcentaje"=>$PorcentajeAcordeResultado, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo);
			
		}//else del fuera de rango
		return $salidaJson;
	}//Peso
	
	function  RangoDePesoPorAltura($altura, $peso)
	{
		if( $altura<140 || $altura>200 || $peso<43)
		{
			 $resultado="Fuera de rango";
		}//if
		else {
		switch($altura)
		{
				case $altura>=140 && $altura<=142:
					if($peso<43)$resultado="Bajo de peso";
					if($peso>=43 && $peso<=53)$resultado="Peso Ideal";
					if($peso>=54 && $peso<=61)$resultado="Sobre Peso";
					if($peso>61)$resultado="Obesidad";
				break;
				
				case $altura>=143 && $altura<=144:
					if($peso<44)$resultado="Bajo de peso";
					if($peso>=44 && $peso<=54)$resultado="Peso Ideal";
					if($peso>=55 && $peso<=65)$resultado="Sobre Peso";
					if($peso>65)$resultado="Obesidad";
				break;
				
				case $altura>=145 && $altura<=146:
					if($peso<45)$resultado="Bajo de peso";
					if($peso>=45 && $peso<=55)$resultado="Peso Ideal";
					if($peso>=56 && $peso<=66)$resultado="Sobre Peso";
					if($peso>66)$resultado="Obesidad";
				break;
				
				case $altura>=147 && $altura<=148:
					if($peso<46)$resultado="Bajo de peso";
					if($peso>=46 && $peso<=56)$resultado="Peso Ideal";
					if($peso>=57 && $peso<=68)$resultado="Sobre Peso";
					if($peso>68)$resultado="Obesidad";
				break;
				
				case $altura>=149 && $altura<=150:
					if($peso<50)$resultado="Bajo de peso";
					if($peso>=50 && $peso<=60)$resultado="Peso Ideal";
					if($peso>=61 && $peso<=71)$resultado="Sobre Peso";
					if($peso>71)$resultado="Obesidad";
				break;
				
				case $altura>=151 && $altura<=152:
					if($peso<51)$resultado="Bajo de peso";
					if($peso>=51 && $peso<=61)$resultado="Peso Ideal";
					if($peso>=62 && $peso<=72)$resultado="Sobre Peso";
					if($peso>72)$resultado="Obesidad";
				break;
				
				case $altura>=153 && $altura<=154:
					if($peso<52)$resultado="Bajo de peso";
					if($peso>=52 && $peso<=62)$resultado="Peso Ideal";
					if($peso>=63 && $peso<=75)$resultado="Sobre Peso";
					if($peso>75)$resultado="Obesidad";
				break;
				
				case $altura>=155 && $altura<=156:
					if($peso<55)$resultado="Bajo de peso";
					if($peso>=55 && $peso<=65)$resultado="Peso Ideal";
					if($peso>=66 && $peso<=75)$resultado="Sobre Peso";
					if($peso>75)$resultado="Obesidad";
				break;
				
				case $altura>=157 && $altura<=158:
					if($peso<56)$resultado="Bajo de peso";
					if($peso>=56 && $peso<=66)$resultado="Peso Ideal";
					if($peso>=67 && $peso<=77)$resultado="Sobre Peso";
					if($peso>77)$resultado="Obesidad";
				break;
				
				case $altura>=159 && $altura<=160:
					if($peso<57)$resultado="Bajo de peso";
					if($peso>=57 && $peso<=70)$resultado="Peso Ideal";
					if($peso>=71 && $peso<=81)$resultado="Sobre Peso";
					if($peso>81)$resultado="Obesidad";
				break;
				
				case $altura>=161 && $altura<=162:
					if($peso<58)$resultado="Bajo de peso";
					if($peso>58 && $peso<=70)$resultado="Peso Ideal";
					if($peso>=71 && $peso<=81)$resultado="Sobre Peso";
					if($peso>81)$resultado="Obesidad";
				break;
				
				case $altura>=163 && $altura<=164:
					if($peso<59)$resultado="Bajo de peso";
					if($peso>=59 && $peso<=73)$resultado="Peso Ideal";
					if($peso>=74 && $peso<=81)$resultado="Sobre Peso";
					if($peso>81)$resultado="Obesidad";
				break;
				
				case $altura>=165 && $altura<=166:
					if($peso<60)$resultado="Bajo de peso";
					if($peso>=60 && $peso<=74)$resultado="Peso Ideal";
					if($peso>=75 && $peso<=86)$resultado="Sobre Peso";
					if($peso>86)$resultado="Obesidad";
				break;
				
				case $altura>=167 && $altura<=168:
					if($peso<62)$resultado="Bajo de peso";
					if($peso>=62 && $peso<=75)$resultado="Peso Ideal";
					if($peso>=76 && $peso<=88)$resultado="Sobre Peso";
					if($peso>88)$resultado="Obesidad";
				break;
				
				case $altura>=169 && $altura<=170:
					if($peso<65)$resultado="Bajo de peso";
					if($peso>=65 && $peso<=78)$resultado="Peso Ideal";
					if($peso>=79 && $peso<=91)$resultado="Sobre Peso";
					if($peso>91)$resultado="Obesidad";
				break;
				
				case $altura>=171 && $altura<=173:
					if($peso<66)$resultado="Bajo de peso";
					if($peso>=66 && $peso<=80)$resultado="Peso Ideal";
					if($peso>=81 && $peso<=94)$resultado="Sobre Peso";
					if($peso>94)$resultado="Obesidad";
				break;
				
				case $altura>=174 && $altura<=175:
					if($peso<67)$resultado="Bajo de peso";
					if($peso>=67 && $peso<=82)$resultado="Peso Ideal";
					if($peso>=83 && $peso<=96)$resultado="Sobre Peso";
					if($peso>96)$resultado="Obesidad";
				break;
				
				case $altura>=176 && $altura<=177:
					if($peso<68)$resultado="Bajo de peso";
					if($peso>=68 && $peso<=84)$resultado="Peso Ideal";
					if($peso>=85 && $peso<=100)$resultado="Sobre Peso";
					if($peso>100)$resultado="Obesidad";
				break;
				
				case $altura>=178 && $altura<=179:
					if($peso<70)$resultado="Bajo de peso";
					if($peso>=70 && $peso<=85)$resultado="Peso Ideal";
					if($peso>=86 && $peso<=101)$resultado="Sobre Peso";
					if($peso>101)$resultado="Obesidad";
				break;
				
				case $altura>=180 && $altura<=181:
					if($peso<71)$resultado="Bajo de peso";
					if($peso>=71 && $peso<=86)$resultado="Peso Ideal";
					if($peso>=87 && $peso<=104)$resultado="Sobre Peso";
					if($peso>104)$resultado="Obesidad";
				break;
				
				case $altura>=182 && $altura<=183:
					if($peso<72)$resultado="Bajo de peso";
					if($peso>=72 && $peso<=88)$resultado="Peso Ideal";
					if($peso>=89 && $peso<=105)$resultado="Sobre Peso";
					if($peso>105)$resultado="Obesidad";
				break;
				
				case $altura>=184 && $altura<=185:
					if($peso<75)$resultado="Bajo de peso";
					if($peso>=75 && $peso<=93)$resultado="Peso Ideal";
					if($peso>=94 && $peso<=110)$resultado="Sobre Peso";
					if($peso>110)$resultado="Obesidad";
				break;
				
				case $altura>=186 && $altura<=187:
					if($peso<76)$resultado="Bajo de peso";
					if($peso>=76 && $peso<=94)$resultado="Peso Ideal";
					if($peso>=95 && $peso<=110)$resultado="Sobre Peso";
					if($peso>110)$resultado="Obesidad";
				break;
				
				case $altura>=188 && $altura<=189:
					if($peso<77)$resultado="Bajo de peso";
					if($peso>=77 && $peso<=95)$resultado="Peso Ideal";
					if($peso>=96 && $peso<=111)$resultado="Sobre Peso";
					if($peso>115)$resultado="Obesidad";
				break;
				
				case $altura>=190 && $altura<=191:
					if($peso<80)$resultado="Bajo de peso";
					if($peso>=80 && $peso<=85)$resultado="Peso Ideal";
					if($peso>=96 && $peso<=115)$resultado="Sobre Peso";
					if($peso>115)$resultado="Obesidad";
				break;
				
				case $altura>=192 && $altura<=193:
					if($peso<82)$resultado="Bajo de peso";
					if($peso>=82 && $peso<=96)$resultado="Peso Ideal";
					if($peso>=97 && $peso<=115)$resultado="Sobre Peso";
					if($peso>115)$resultado="Obesidad";
				break;
				
				case $altura>=190 && $altura<=191:
					if($peso<80)$resultado="Bajo de peso";
					if($peso>=80 && $peso<=95)$resultado="Peso Ideal";
					if($peso>=96 && $peso<=115)$resultado="Sobre Peso";
					if($peso>115)$resultado="Obesidad";
				break;
				
				case $altura>=192&& $altura<=193:
					if($peso<82)$resultado="Bajo de peso";
					if($peso>=82 && $peso<=96)$resultado="Peso Ideal";
					if($peso>=97 && $peso<=115)$resultado="Sobre Peso";
					if($peso>115)$resultado="Obesidad";
				break;
				
				case $altura>=194 && $altura<=195:
					if($peso<84)$resultado="Bajo de peso";
					if($peso>=84 && $peso<=103)$resultado="Peso Ideal";
					if($peso>=104 && $peso<=120)$resultado="Sobre Peso";
					if($peso>120)$resultado="Obesidad";
				break;
				
				case $altura>=196 && $altura<=197:
					if($peso<85)$resultado="Bajo de peso";
					if($peso>=85 && $peso<=104)$resultado="Peso Ideal";
					if($peso>=105 && $peso<=121)$resultado="Sobre Peso";
					if($peso>121)$resultado="Obesidad";
				break;
				
				case $altura>=198 && $altura<=199:
					if($peso<86)$resultado="Bajo de peso";
					if($peso>=86 && $peso<=108)$resultado="Peso Ideal";
					if($peso>=109 && $peso<=122)$resultado="Sobre Peso";
					if($peso>122)$resultado="Obesidad";
				break;
				case $altura>=200:
					if($peso<88)$resultado="Bajo de peso";
					if($peso>=88 && $peso<=108)$resultado="Peso Ideal";
					if($peso>=109 && $peso<=127)$resultado="Sobre Peso";
					if($peso>127)$resultado="Obesidad";
				break;
		}//switch
		}//else
		
		return $resultado;
	}//RangoDePesoPorAltura

	//Funciones de IMC///
	function IMC1($id_Cliente,$Altura,$id_instructor,$peso)
	{
		$agregar   = new Agregar();
		$consultar = new Consultar();
		
		//Sacando el índice de masa corporal
		$alturaMetros   = $Altura/100; //convirtiéndo la altura en metros
		$alturaCuadrada = $alturaMetros*$alturaMetros;
		$imc			= number_format($peso/$alturaCuadrada,2); //Dejando el númnero con solo 2 decimales	
		$Diagnostico	= DiagnosticoAcordeResultadoIMC($imc);
		//Verificando si está dentro del rango 
		if($Diagnostico == "Fuera de rango")
		{
			
		}
		else
		{
		 $porcentajeAcordeResultado = PorcentajeAcordeResultadoPeso($Diagnostico);
		
		//Agregando el resultado en la BD
		$TipoPrueba = 3;
		$DescPrueba = "IMC";
		//Guardando los resultados de la prueba
		$resAgregar=$agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Diagnostico, $porcentajeAcordeResultado,$imc);
		
		//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
		$resultadoPruebas=$consultar->_ConsultarResultadosPruebas(3,$id_Cliente);
		
		//Creando un array con los resultados de las pruebas
			$arrayResultados=array();
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje=($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado=$fila['Resultado'];
				$Prueba=array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}//for
			
			//Tomando el consejo de la evaluación física
			$resultConsejo=$consultar->_ConsultarConsejoAcordeResultado(3,$Diagnostico);
			$filaConsejo=$resultConsejo->fetch_assoc();
			$consejo=$filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson=array("Condicion"=>$Diagnostico, "Porcentaje"=>$porcentajeAcordeResultado, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo, "CantidadResultado"=>$imc);
		}
			return $salidaJson;
	}

	function CondicionFisica($id_Cliente,$ResultadoEvaluado,$id_instructor)
	{
		$consultar = new Consultar(); 
		
		//Buscando la información del cliente para hacer el test
		$consultar = new Consultar();
		$result	   = $consultar->_ConsultarClientesPorId($id_Cliente);	
		$fila	   = $result->fetch_assoc();
		//tomando sexo y edad
		$edad	   = $fila['num_edad'];
		$sexo	   = $fila['de_genero'];
		$DescPrueba = "Condicion Física";
		if($sexo=="MASCULINO")	
		{
			$Condicion = EvaluacionMasculinaCondicionFisica($edad,$ResultadoEvaluado); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			if($Condicion=="Fuera de rango")
			{
				$salidaJson=array("Tipo_Prueba"=>1, "Resultado"=>$Condicion, "id_cliente"=>$id_Cliente,"id_instructor"=>$id_instructor,
				"Resultado_Evaluado"=>$ResultadoEvaluado);
			}
			else
			{	
				$salidaJson = AgregadoYfuncionesDeCondicionFisica($id_instructor,$id_Cliente,$Condicion,$ResultadoEvaluado,$DescPrueba); 
			}
			return $salidaJson;
		}//if
		else
		{
			$Condicion = EvaluacionFemeninaCondicionFisica($edad,$ResultadoEvaluado); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			if($Condicion=="Fuera de rango")
			{
				$salidaJson=array("Tipo_Prueba"=>1, "Resultado"=>$Condicion, "id_cliente"=>$id_Cliente,"id_instructor"=>$id_instructor,
				"Resultado_Evaluado"=>$ResultadoEvaluado);
			}
			else{$salidaJson=AgregadoYfuncionesDeCondicionFisica($id_instructor,$id_Cliente,$Condicion,$ResultadoEvaluado,$DescPrueba); }
			return $salidaJson;
		}
		
	}//CondicionFisica
	

	//Función para determinar el rango de edades en el que se encuentra la persona
	function EvaluacionMasculinaCondicionFisica($edad, $resultado)
	{
		$res="";
		if($res=="" && $resultado<49 || $resultado>82)
		{
			 $res="Fuera de rango";
		}//if
		else{
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=25:
				
					if($res=="" && $resultado>=49 && $resultado<=55) $res="Atleta";
					if($res=="" && $resultado>=56 && $resultado<=61) $res="Excelente";	
					if($res=="" && $resultado>=62 && $resultado<=69) $res="Bueno";	
					if($res=="" && $resultado>=70 && $resultado<=81) $res="Promedio";
					if($res=="" && $resultado>=82)$res="Pobre";
				break;
				
				case $edad >=26 && $edad<=35:
					if($res=="" && $resultado>=49 && $resultado<=54) $res="Atleta";
					if($res=="" && $resultado>=55 && $resultado<=61) $res="Excelente";
					if($res=="" && $resultado>=62 && $resultado<=70) $res="Bueno";
					if($res=="" && $resultado>=71 && $resultado<=81) $res="Promedio";
					if($res=="" && $resultado>=82)$res="Pobre";
				break;
					
				case $edad >=36 && $edad<=45:
					if($res=="" && $resultado>=50 && $resultado<=56) $res="Atleta";
					if($res=="" && $resultado>=57 && $resultado<=62) $res="Excelente";
					if($res=="" && $resultado>=63 && $resultado<=70) $res="Bueno";
					if($res=="" && $resultado>=71 && $resultado<=82) $res="Promedio";
					if($res=="" && $resultado>=83)					 $res="Pobre";
				break;
				
				case $edad >=46 && $edad<=55:
					if($res=="" && $resultado>=50 && $resultado<=57) $res="Atleta";
					if($res=="" && $resultado>=58 && $resultado<=63) $res="Excelente";
					if($res=="" && $resultado>=64 && $resultado<=71) $res="Bueno";
					if($res=="" && $resultado>=72 && $resultado<=83) $res="Promedio";
					if($res=="" && $resultado>=84)					 $res="Pobre";
				break;
				
				case $edad >=56 && $edad<=65:
					if($res=="" && $resultado>=51 && $resultado<=56) $res="Atleta";
					if($res=="" && $resultado>=57 && $resultado<=61) $res="Excelente";
					if($res=="" && $resultado>=62 && $resultado<=71) $res="Bueno";
					if($res=="" && $resultado>=72 && $resultado<=81) $res="Promedio";
					if($res=="" && $resultado>=82)					 $res="Pobre";
				break;
				
				case $edad >65:
					if($res=="" && $resultado>=50 && $resultado<=55) $res="Atleta";
					if($res=="" && $resultado>=56 && $resultado<=61) $res="Excelente";
					if($res=="" && $resultado>=62 && $resultado<=69) $res="Bueno";
					if($res=="" && $resultado>=70 && $resultado<=79) $res="Promedio";
					if($res=="" && $resultado>=80)					 $res="Pobre";
				break;
				
				default:
					$res="Fuera de rango";
				break;
				
			}//switch
		}//else
			$condicion = $res;
			return $condicion;
	}//EvaluacionMasculina
	
	function EvaluacionFemeninaCondicionFisica($edad, $resultado)
	{
		$res="";
		if($res=="" && $resultado<49 || $resultado>82)
		{
			 $res="Fuera de rango";
		}//if
		else{
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=25:
						if($res=="" && $resultado>=50 && $resultado<=60) $res="Atleta";
						if($res=="" && $resultado>=61 && $resultado<=68) $res="Excelente";	
						if($res=="" && $resultado>=69 && $resultado<=73) $res="Bueno";	
						if($res=="" && $resultado>=74 && $resultado<=84) $res="Promedio";
						if($res=="" && $resultado>=85)					 $res="Pobre";						
				break;
				
				case $edad >=26 && $edad<=35:
					if($res=="" && $resultado>=50 && $resultado<=59)$res="Atleta";
					if($res=="" && $resultado>=60 && $resultado<=64)$res="Excelente";
					if($res=="" && $resultado>=65 && $resultado<=72)$res="Bueno";
					if($res=="" && $resultado>=73 && $resultado<=82)$res="Promedio";
					if($res=="" && $resultado>=83)$res="Pobre";
				break;
					
				case $edad >=36 && $edad<=45:
					if($res=="" && $resultado>=50 && $resultado<=59)$res="Atleta";
					if($res=="" && $resultado>=60 && $resultado<=64)$res="Excelente";
					if($res=="" && $resultado>=65 && $resultado<=73)$res="Bueno";
					if($res=="" && $resultado>=74 && $resultado<=84)$res="Promedio";
					if($res=="" && $resultado>=85)$res="Pobre";
				break;
				
				case $edad >=46 && $edad<=55:
					if($res=="" && $resultado>=50 && $resultado<=60)$res="Atleta";
					if($res=="" && $resultado>=61 && $resultado<=65)$res="Excelente";
					if($res=="" && $resultado>=66 && $resultado<=73)$res="Bueno";
					if($res=="" && $resultado>=74 && $resultado<=83)$res="Promedio";
					if($res=="" && $resultado>=84)$res="Pobre";
				break;
				
				case $edad >=56 && $edad<=65:
					if($res=="" && $resultado>=50 && $resultado<=59)$res="Atleta";
					if($res=="" && $resultado>=60 && $resultado<=64)$res="Excelente";
					if($res=="" && $resultado>=65 && $resultado<=73)$res="Bueno";
					if($res=="" && $resultado>=74 && $resultado<=83)$res="Promedio";
					if($res=="" && $resultado>=84)$res="Pobre";
				break;
				
				case $edad >65:
					if($res=="" && $resultado>=50 && $resultado<=59)$res="Atleta";
					if($res=="" && $resultado>=60 && $resultado<=64)$res="Excelente";
					if($res=="" && $resultado>=65 && $resultado<=72)$res="Bueno";
					if($res=="" && $resultado>=73 && $resultado<=84)$res="Promedio";
					if($res=="" && $resultado>=84)$res="Pobre";
				break;
				
				
				default:
					$res="Fuera de rango";
				break;
				
			}//switch
	}//else
			$condicion=$res;
			return $condicion;
	}
	
	function PorcentajeAcordeResultado($resultado)
	{
		$porcentaje = 0;
		switch($resultado)	
		{
			case 'Atleta':    $porcentaje = 100; break;
			case 'Excelente': $porcentaje = 80;  break;
			case 'Bueno':     $porcentaje = 60;  break;
			case 'Promedio':  $porcentaje = 40;  break;
			case 'Pobre':     $porcentaje = 20;  break;
		}//switch
		
		return $porcentaje;
	}//PorcentajeAcordeResultado
	
	function AgregadoYfuncionesDeCondicionFisica($id_instructor,$id_Cliente,$Condicion,$ResultadoEvaluado,$DescPrueba)
	{
			$agregar    = new Agregar();
			$consultar  = new Consultar(); 
			//Tomando los valores para guardar el resultado de la prueba en la BD
			$TipoPrueba = 1;
			$porcentaje = PorcentajeAcordeResultado($Condicion);
			//Guardando los resultados de la prueba
			$resultado=$agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Condicion, $porcentaje,$ResultadoEvaluado);
			
			//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
			$resultadoPruebas=$consultar->_ConsultarResultadosPruebas(1,$id_Cliente);
			
			//Creando un array con los resultados de las pruebas
			$arrayResultados=array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje = ($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado  = $fila['Resultado'];
				$Prueba     = array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}
			//print_r($arrayResultados);
			//Tomando el consejo de la evaluación física
			$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(1,$Condicion);
			$filaConsejo   = $resultConsejo->fetch_assoc();
			$consejo	   = $filaConsejo['Consejo'];
			
			//Guardando en la bitácora el biotest hecho
			$ResultBitacora = $agregar->_AgregarRegistroBitacoraBiotest($id_instructor,$id_Cliente);
			//DEvolviendo parámetros para la notificación				
			$salidaJson=array("Condicion"=>$Condicion, "Resultado"=>$ResultadoEvaluado, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo);
			return $salidaJson;
	}
	
	function ConvertirTimeStamp($time) //función para devolver un string de la fecha a partir de una time stamp de mysql
	{
		//Quitando las horas y minutos
		$fechas		    = explode(" ",$time);
		$fechaSinHoras  = $fechas[0];
		//Seperando los elementos de año, mes y día.
		$ElementosFecha = explode("-",$fechaSinHoras);
		$year			= $ElementosFecha[0];
		@$month			= $ElementosFecha[1];
		@$day			= $ElementosFecha[2];
		$mes="";
		//Asignando el nombre del mes correspondiente
			if($month==1) $mes = "Enero";
			if($month==2) $mes = "Febrero";
			if($month==3) $mes = "Marzo";
			if($month==4) $mes = "Abril";
			if($month==5) $mes = "Mayo";
			if($month==6) $mes = "Junio";
			if($month==7) $mes = "Julio";
			if($month==8) $mes = "Agosto";
			if($month==9) $mes = "Septiembre";
			if($month==10)$mes = "Octubre";
			if($month==11)$mes = "Noviembre";
			if($month==12)$mes = "Diciembre";	
		
		//Devolviendo el string de la fecha adecuado.
		$Fechafinal = $day." de ".$mes." de ".$year;
		return $Fechafinal;
	}
	
	
	

	function PorcentajeAcordeResultadoPeso($resultado)
	{
		switch($resultado)	
		{
			case 'Peso Ideal': 	 $porcentaje=100; break;
			case 'Sobre Peso': 	 $porcentaje=60;  break;
			case 'Bajo de peso': $porcentaje=60;  break;
			case 'Obesidad':     $porcentaje=20;  break;
		}//switch
		
		return $porcentaje;
	}//PorcentajeAcordeResultado
	
	
	
	function DiagnosticoAcordeResultadoIMC($resultado)
	{
		switch($resultado)
		{
			case $resultado<18.5:
				$resultado="Bajo de peso";	
			break;	
			
			case $resultado>=18.5 && $resultado<=24.99:
				$resultado="Peso Ideal";	
			break;	
			
			case $resultado>=25 && $resultado<=29.99:
				$resultado="Sobre Peso";	
			break;	
			
			case $resultado>=30:
				$resultado="Obesidad";	
			break;	
			
			default :
			$resultado="Fuera de rango";	
			break;
		}//switch
		return $resultado;
	}//	function PorcentajeAcordeResultadoIMC($resultado)
	
	
	//Funciones de IMM Masa muscular //
	function IMM($id_Cliente,$id_instructor,$Cintura, $Cadera,$Perimetro_Espalda, $Perimetro_Pecho,$Perimetro_brazo_relajado, 
			$Perimetro_brazo_flexionado,$Perimetro_femoral,$Perimetro_Pantorrilla )
	{
		//Se agregan los 8 resultados a laspruebas concatenando el IMM junto al nombre de la prueba
		//Tomando los valores para guardar el resultado de la prueba en la BD
			$agregar   = new Agregar();
			$consultar = new Consultar();
			
			
			//Como no pueden ejecutarse múltiples query en un ciclo se hace el multiquery
			$resultMul=MultiQuery($id_Cliente,$id_instructor,$Cintura, $Cadera,$Perimetro_Espalda, $Perimetro_Pecho,$Perimetro_brazo_relajado, 
			$Perimetro_brazo_flexionado,$Perimetro_femoral,$Perimetro_Pantorrilla);
			
			//Obteniendo pruebas de los últimos 2 meses para saber el resultado de la persona en las gráficas
			
			//$resultadoPruebas=ConsultarResultadosPruebasIMM(4,$id_Cliente);
			//print_r($fila);
			
			//Creando un array con los resultados de las pruebas
			
			//Vectores creados en el archivo de_Agregar.php
			$arrayResultados  = $_SESSION['arrayResultados'];
			$arrayResultados2 = $_SESSION['arrayResultados2'];
			//print_r($arrayResultados2);
			//tomando los resultados
			//Estos dos resultados siempre volvían en TRUE, no supe por que pero fue la única manera que pude 
			//mandarlos como debía ser al front end
			$Perimetro_Espalda2 	   = $arrayResultados2[2]['PorcentResultadoNumericoaje'];
			$Perimetro_brazo_relajado2 = $arrayResultados2[4]['PorcentResultadoNumericoaje'];
			$salidaJson=array( "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,"Resultados2"=>$arrayResultados2,
							   "TipoPrueba"=>4, "Perimetro_Espalda2"=>$Perimetro_Espalda2, "Perimetro_brazo_relajado2"=>$Perimetro_brazo_relajado2);
			return $salidaJson;							   
	} //IMM
	
	function MultiQuery($id_cliente,$id_instructor,$Cintura, $Cadera,$Perimetro_Espalda, $Perimetro_Pecho,$Perimetro_brazo_relajado, 
			$Perimetro_brazo_flexionado,$Perimetro_femoral,$Perimetro_Pantorrilla)
	{
			$tipo_prueba=4; //índice de masa corporal es la prueba 4
			$Condicion="No Aplica";
			$porcentaje =0;
			
			$DescPrueba1 = "IMM - Cintura";
			$DescPrueba2 = "IMM - Cadera";
			$DescPrueba3 = "IMM - Perimetro_Espalda";
			$DescPrueba4 = "IMM - Perimetro_Pecho";
			$DescPrueba5 = "IMM - Perimetro_brazo_relajado";
			$DescPrueba6 = "IMM - Perimetro_brazo_flexionado";
			$DescPrueba7 = "IMM - Perimetro_femoral";
			$DescPrueba8 = "IMM - Perimetro_Pantorrilla";
		$query='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba1.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Cintura.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba2.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Cadera.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba3.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_Espalda.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba4.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_Pecho.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba5.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_brazo_relajado.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba6.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_brazo_flexionado.'");';
		
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba7.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_femoral.'");';
		$query .='
		INSERT INTO sg_pruebas (id_instructor,id_cliente,Tipo_Prueba,Desc_Prueba, Resultado, Porcentaje,resultado_numerico)
		VALUES("'.$id_instructor.'", "'.$id_cliente.'", "'.$tipo_prueba.'", "'.$DescPrueba8.'", "'.$Condicion.'", "'.$porcentaje.'", "'.$Perimetro_Pantorrilla.'");';
		
		$query .='	select * from sg_pruebas pruebas 
			where Tipo_Prueba="'.$tipo_prueba.'" 
			and id_cliente="'.$id_cliente.'" order by fecha desc limit 16;';
		
		$agregar   = new Agregar();
		$resultado = $agregar->_AgregarResultadoMultiQuery($id_cliente, $query);
		return $resultado;
	}
	
	
	///Funciones de Stamina////
	function Stamina($id_Cliente,$repeticiones, $id_instructor)
	{
		//Buscando los datos del cliente
		$consultar=new Consultar();
		
		$result = $consultar->_ConsultarClientesPorId($id_Cliente);
		$fila	= $result->fetch_assoc();
		//tomando sexo y edad
		$edad = $fila['num_edad'];
		$sexo = $fila['de_genero'];
		
		if($sexo=="MASCULINO")	
		{
			$Condicion  = EvaluacionMasculinaStamina($edad,$repeticiones); //resultado de la prueba
			$salidaJson = AgregarResultadosYDevolverInformacionStamina($id_instructor,$id_Cliente,$Condicion,$repeticiones);
			return $salidaJson;
		}//if
		else
		{
			$Condicion = EvaluacionFemeninaStamina($edad,$repeticiones); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			$salidaJson = AgregarResultadosYDevolverInformacionStamina($id_instructor,$id_Cliente,$Condicion,$repeticiones); 
			return $salidaJson;
		}
		
		
	}//Stamina
	
	function EvaluacionMasculinaStamina($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=25:
				
						if($res=="" && $resultado<79) 					   $res="Atleta";
						if($res=="" && $resultado>=79 && $resultado<=89)   $res="Excelente";	
						if($res=="" && $resultado>=90 && $resultado<=105)  $res="Bueno";	
						if($res=="" && $resultado>=106 && $resultado<=128) $res="Promedio";
						if($res=="" && $resultado>128)					   $res="Pobre";
				break;
				
				case $edad >=26 && $edad<=35:
					if($res=="" && $resultado<81)						   $res = "Atleta";
					if($res=="" && $resultado>=81 && $resultado<=89)	   $res = "Excelente";
					if($res=="" && $resultado>=90 && $resultado<=107)	   $res = "Bueno";
					if($res=="" && $resultado>=108 && $resultado<=128)	   $res = "Promedio";
					if($res=="" && $resultado>128)						   $res = "Pobre";
				break;
					
				case $edad >=36 && $edad<=45:
					if($res=="" && $resultado<83)$res="Atleta";
					if($res=="" && $resultado>=83 && $resultado<=96)$res="Excelente";
					if($res=="" && $resultado>=97 && $resultado<=112)$res="Bueno";
					if($res=="" && $resultado>=113 && $resultado<=130)$res="Promedio";
					if($res=="" && $resultado>130)$res="Pobre";
				break;
				
				case $edad >=46 && $edad<=55:
					if($res=="" && $resultado<87)$res="Atleta";
					if($res=="" && $resultado>=87 && $resultado<=97)$res="Excelente";
					if($res=="" && $resultado>=98 && $resultado<=116)$res="Bueno";
					if($res=="" && $resultado>=117 && $resultado<=132)$res="Promedio";
					if($res=="" && $resultado>132)$res="Pobre";
				break;
				
				case $edad >=56 && $edad<=65:
					if($res=="" && $resultado<86)$res="Atleta";
					if($res=="" && $resultado>=86 && $resultado<=97)$res="Excelente";
					if($res=="" && $resultado>=98 && $resultado<=112)$res="Bueno";
					if($res=="" && $resultado>=113 && $resultado<=129)$res="Promedio";
					if($res=="" && $resultado>129)$res="Pobre";
				break;
				
				case $edad >65:
					if($res=="" && $resultado<88)$res="Atleta";
					if($res=="" && $resultado>=88 && $resultado<=96)$res="Excelente";
					if($res=="" && $resultado>=97 && $resultado<=113)$res="Bueno";
					if($res=="" && $resultado>=114 && $resultado<=130)$res="Promedio";
					if($res=="" && $resultado>130)$res="Pobre";
				break;
				
			}//switch
			$condicion=$res;
			return $condicion;
	}//EvaluacionMasculinaStamina
	
	function EvaluacionFemeninaStamina($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=25:
				
						if($res=="" && $resultado<85) 					    $res = "Atleta";
						if($res=="" && $resultado>=85  && $resultado<=98)   $res = "Excelente";	
						if($res=="" && $resultado>=99  && $resultado<=117)  $res = "Bueno";	
						if($res=="" && $resultado>=118 && $resultado<=140)  $res = "Promedio";
						if($res=="" && $resultado>140)					    $res = "Pobre";
				break;
				
				case $edad >=26 && $edad<=35:
					if($res=="" && $resultado<88)							$res = "Atleta";
					if($res=="" && $resultado>=88 && $resultado<=99)		$res = "Excelente";
					if($res=="" && $resultado>=100 && $resultado<=119)		$res = "Bueno";
					if($res=="" && $resultado>=120 && $resultado<=138)		$res = "Promedio";
					if($res=="" && $resultado>138)							$res = "Pobre";
				break;
					
				case $edad >=36 && $edad<=45:
					if($res=="" && $resultado<90)							$res = "Atleta";
					if($res=="" && $resultado>=90 && $resultado<=102)		$res = "Excelente";
					if($res=="" && $resultado>=103 && $resultado<=118)		$res = "Bueno";
					if($res=="" && $resultado>=119 && $resultado<=140)		$res = "Promedio";
					if($res=="" && $resultado>140)							$res = "Pobre";
				break;
				
				case $edad >=46 && $edad<=55:
					if($res=="" && $resultado<94)							$res = "Atleta";
					if($res=="" && $resultado>=94 && $resultado<=104)		$res = "Excelente";
					if($res=="" && $resultado>=105 && $resultado<=120)		$res = "Bueno";
					if($res=="" && $resultado>=121 && $resultado<=135)		$res = "Promedio";
					if($res=="" && $resultado>135)							$res = "Pobre";
				break;
				
				case $edad >=56 && $edad<=65:
					if($res=="" && $resultado<95)							$res = "Atleta";
					if($res=="" && $resultado>=95 && $resultado<=104)		$res = "Excelente";
					if($res=="" && $resultado>=105 && $resultado<=118)		$res = "Bueno";
					if($res=="" && $resultado>=119 && $resultado<=139)		$res = "Promedio";
					if($res=="" && $resultado>139)							$res = "Pobre";
				break;
				
				case $edad >65:
					if($res=="" && $resultado<90)							$res = "Atleta";
					if($res=="" && $resultado>=90 && $resultado<=102)		$res = "Excelente";
					if($res=="" && $resultado>=103 && $resultado<=122)		$res = "Bueno";
					if($res=="" && $resultado>=123 && $resultado<=134)		$res = "Promedio";
					if($res=="" && $resultado>134)							$res = "Pobre";
				break;
				
			}//switch
			$condicion = $res;
			return $condicion;
	}//EvaluacionMasculinaStamina
	
	function AgregarResultadosYDevolverInformacionStamina($id_instructor,$id_Cliente,$Condicion,$repeticiones)
	{
		$agregar   = new Agregar();
		$consultar = new Consultar();
		//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			
			//Tomando los valores para guardar el resultado de la prueba en la BD
			$TipoPrueba		  = 5;
			$DescPrueba		  = "Stamina";
			$porcentajeActual = PorcentajeAcordeResultado($Condicion);
			//Guardando los resultados de la prueba
			$resultado = $agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Condicion, $porcentajeActual,$repeticiones);
			
			//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
			$resultadoPruebas=$consultar->_ConsultarResultadosPruebas(5,$id_Cliente);
			
			//Creando un array con los resultados de las pruebas
			$arrayResultados = array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje=($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado=$fila['Resultado'];
				$Prueba=array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}
			//print_r($arrayResultados);
			
			//Tomando el consejo de la evaluación física
			$resultConsejo=$consultar->_ConsultarConsejoAcordeResultado(5,$Condicion); //El número es el tipo de prueba
			$filaConsejo=$resultConsejo->fetch_assoc();
			$consejo=$filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson=array("Condicion"=>$Condicion, "Resultado"=>$repeticiones, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo, "Porcentaje"=>$porcentajeActual);
			return $salidaJson;	
	}
	
	function ConsultarResultadosPruebasIMM($tipo_prueba, $id_cliente) //consulta el último biotest para imm se necesitan 16 campos.
	{
		$query='
			select * from sg_pruebas pruebas 
			where Tipo_Prueba="'.$tipo_prueba.'" 
			and id_cliente="'.$id_cliente.'" order by pruebas.id DESC limit 16
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}
	
	//Métodos de la FUERZA//
	function Fuerza($id_Cliente,$repeticiones, $id_instructor)
	{
		//Buscando los datos del cliente
		$consultar=new Consultar();
		
		
		$result=$consultar->_ConsultarClientesPorId($id_Cliente);
		$fila=$result->fetch_assoc();
		//tomando sexo y edad
		$edad=$fila['num_edad'];
		$sexo=$fila['de_genero'];
		if($sexo=="MASCULINO")	
		{
			$Condicion  = EvaluacionMasculinaFuerza($edad,$repeticiones); //resultado de la prueba
			$salidaJson = AgregarResultadosYDevolverInformacionFuerza($id_instructor,$id_Cliente,$Condicion,$repeticiones);
			return $salidaJson;
		}//if
		else
		{
			$Condicion  = EvaluacionFemeninaFuerza($edad,$repeticiones); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			$salidaJson	= AgregarResultadosYDevolverInformacionFuerza($id_instructor,$id_Cliente,$Condicion,$repeticiones); 
			return $salidaJson;
		}
		
		
	}//Fuerza
	
	function EvaluacionMasculinaFuerza($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=29:
				
						if($res=="" && $resultado>54) 					   $res="Atleta";
						if($res=="" && $resultado>=45 && $resultado<=54)   $res="Excelente";	
						if($res=="" && $resultado>=35 && $resultado<=44)   $res="Bueno";	
						if($res=="" && $resultado>=20 && $resultado<=34)   $res="Promedio";
						if($res=="" && $resultado<20)					   $res="Pobre";
				break;
				
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>44)							$res="Atleta";
					if($res=="" && $resultado>=35 && $resultado<=44)		$res="Excelente";
					if($res=="" && $resultado>=24 && $resultado<=34)		$res="Bueno";
					if($res=="" && $resultado>=15 && $resultado<=24)		$res="Promedio";
					if($res=="" && $resultado<15)							$res="Pobre";
				break;
					
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>39)							$res="Atleta";
					if($res=="" && $resultado>=30 && $resultado<=39)		$res="Excelente";
					if($res=="" && $resultado>=20 && $resultado<=29)		$res="Bueno";
					if($res=="" && $resultado>=12 && $resultado<=19)		$res="Promedio";
					if($res=="" && $resultado<12)							$res="Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>34)							$res="Atleta";
					if($res=="" && $resultado>=25 && $resultado<=34)		$res="Excelente";
					if($res=="" && $resultado>=15 && $resultado<=24)		$res="Bueno";
					if($res=="" && $resultado>=8 && $resultado<=14)			$res="Promedio";
					if($res=="" && $resultado<8)							$res="Pobre";
				break;
				
				case $edad >=60:
					if($res=="" && $resultado>29)							$res="Atleta";
					if($res=="" && $resultado>=20 && $resultado<=29)		$res="Excelente";
					if($res=="" && $resultado>=10 && $resultado<=19)		$res="Bueno";
					if($res=="" && $resultado>=5 && $resultado<=9)			$res="Promedio";
					if($res=="" && $resultado<5)							$res="Pobre";
				break;
				
			}//switch
			$condicion=$res;
			return $condicion;
	}//EvaluacionMasculinaFuerza
	
	function EvaluacionFemeninaFuerza($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=18 && $edad<=29:
				
						if($res=="" && $resultado>48) 					   $res="Atleta";
						if($res=="" && $resultado>=34 && $resultado<=48)   $res="Excelente";	
						if($res=="" && $resultado>=17 && $resultado<=33)   $res="Bueno";	
						if($res=="" && $resultado>=6  && $resultado<=16)   $res="Promedio";
						if($res=="" && $resultado<6)					   $res="Pobre";
				break;
				
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>39)						   $res="Atleta";
					if($res=="" && $resultado>=25 && $resultado<=39)	   $res="Excelente";
					if($res=="" && $resultado>=12 && $resultado<=24)	   $res="Bueno";
					if($res=="" && $resultado>=4 && $resultado<=11)		   $res="Promedio";
					if($res=="" && $resultado<4)						   $res="Pobre";
				break;
					
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>34)						   $res="Atleta";
					if($res=="" && $resultado>=20 && $resultado<=34)	   $res="Excelente";
					if($res=="" && $resultado>=8 && $resultado<=19)		   $res="Bueno";
					if($res=="" && $resultado>=3 && $resultado<=7)		   $res="Promedio";
					if($res=="" && $resultado<3)						   $res="Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>29)						   $res="Atleta";
					if($res=="" && $resultado>=15 && $resultado<=29)	   $res="Excelente";
					if($res=="" && $resultado>=6  && $resultado<=14)	   $res="Bueno";
					if($res=="" && $resultado>=2  && $resultado<=5)		   $res="Promedio";
					if($res=="" && $resultado<2)						   $res="Pobre";
				break;
				
				case $edad >=60:
					if($res=="" && $resultado>19)						   $res="Atleta";
					if($res=="" && $resultado>=5 && $resultado<=19)		   $res="Excelente";
					if($res=="" && $resultado>=3 && $resultado<=4)		   $res="Bueno";
					if($res=="" && $resultado>=1 && $resultado<=2)		   $res="Promedio";
					if($res=="" && $resultado<1)						   $res="Pobre";
				break;
				
			}//switch
			$condicion = $res;
			return $condicion;
	}//EvaluacionMasculinaStamina
	
	function AgregarResultadosYDevolverInformacionFuerza($id_instructor,$id_Cliente,$Condicion,$repeticiones)
	{
		$agregar   = new Agregar();
		$consultar = new Consultar();
		//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			
			//Tomando los valores para guardar el resultado de la prueba en la BD
			$TipoPrueba		  = 6;
			$DescPrueba		  = "Fuerza";
			$porcentajeActual = PorcentajeAcordeResultadoFuerza($Condicion);
			//Guardando los resultados de la prueba
			$resultado = $agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Condicion, $porcentajeActual,$repeticiones);
			
			//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
			$resultadoPruebas = $consultar->_ConsultarResultadosPruebas(6,$id_Cliente);
			
			//Creando un array con los resultados de las pruebas
			$arrayResultados = array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje = ($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado  = $fila['Resultado'];
				$Prueba		= array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}
			//print_r($arrayResultados);
			
			//Tomando el consejo de la evaluación física
			$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(6,$Condicion); //El número es el tipo de prueba
			$filaConsejo   = $resultConsejo->fetch_assoc();
			$consejo	   = $filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson	   = array("Condicion"=>$Condicion, "Resultado"=>$repeticiones, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo, "Porcentaje"=>$porcentajeActual);
			return $salidaJson;	
	}//AgregarResultadosYDevolverInformacionFuerza
	
	function PorcentajeAcordeResultadoFuerza($resultado)
	{
		$porcentaje = 0;
		switch($resultado)	
		{
			case 'Atleta': 	 $porcentaje = 100; break;
			case 'Excelente': 		 $porcentaje = 80;  break;
			case 'Bueno': 	 $porcentaje = 60;  break;
			case 'Promedio':     	 $porcentaje = 40;  break;
			case 'Pobre':    $porcentaje = 20;  break;
		}//switch
		
		return $porcentaje;
	}//PorcentajeAcordeResultado
	
	//Apartado de Resistencia//	
	function Resistencia($id_Cliente,$repeticiones, $id_instructor)
	{
		//Buscando los datos del cliente
		$consultar = new Consultar();
		
		
		$result = $consultar->_ConsultarClientesPorId($id_Cliente);
		$fila	= $result->fetch_assoc();
		//tomando sexo y edad
		$edad	= $fila['num_edad'];
		$sexo	= $fila['de_genero'];
		if($sexo=="MASCULINO")	
		{
			$Condicion  = EvaluacionMasculinaResistencia($edad,$repeticiones); //resultado de la prueba
			$salidaJson = AgregarResultadosYDevolverInformacionResistencia($id_instructor,$id_Cliente,$Condicion,$repeticiones);
			return $salidaJson;
		}//if
		else
		{
			$Condicion  = EvaluacionFemeninaResistencia($edad,$repeticiones); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			$salidaJson = AgregarResultadosYDevolverInformacionResistencia($id_instructor,$id_Cliente,$Condicion,$repeticiones); 
			return $salidaJson;
		}
	}//Resistencia
	
	function  EvaluacionMasculinaResistencia($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=15 && $edad<=19:
						if($res=="" && $resultado>=25) 					   $res="Atleta";
						if($res=="" && $resultado>=23 && $resultado<=24)   $res="Excelente";	
						if($res=="" && $resultado>=21 && $resultado<=22)   $res="Bueno";	
						if($res=="" && $resultado>=16 && $resultado<=20)   $res="Promedio";
						if($res=="" && $resultado<16)					   $res="Pobre";
				break;
				
				case $edad >=20 && $edad<=29:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=23 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=21 && $resultado<=22)   $res="Bueno";	
					if($res=="" && $resultado>=13 && $resultado<=20)   $res="Promedio";
					if($res=="" && $resultado<13)					   $res="Pobre";
				break;
					
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=23 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=21 && $resultado<=22)   $res="Bueno";	
					if($res=="" && $resultado>=13 && $resultado<=20)   $res="Promedio";
					if($res=="" && $resultado<13)					   $res="Pobre";

				break;
				
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=22 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=16 && $resultado<=21)   $res="Bueno";	
					if($res=="" && $resultado>=11 && $resultado<=15)   $res="Promedio";
					if($res=="" && $resultado<11)					   $res="Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=20 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=14 && $resultado<=19)   $res="Bueno";	
					if($res=="" && $resultado>=9  && $resultado<=13)   $res="Promedio";
					if($res=="" && $resultado<9)					   $res="Pobre";
				break;
				
				case $edad >=60 && $edad<=69:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=16 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=10 && $resultado<=15)   $res="Bueno";	
					if($res=="" && $resultado>=4  && $resultado<=9)    $res="Promedio";
					if($res=="" && $resultado<4)					   $res="Pobre";
				break;
				
			}//switch
			$condicion=$res;
			return $condicion;
	}// evaluación masculina resistencia
	
	function  EvaluacionFemeninaResistencia($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=15 && $edad<=19:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=23 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=21 && $resultado<=22)   $res="Bueno";	
					if($res=="" && $resultado>=16 && $resultado<=20)   $res="Promedio";
					if($res=="" && $resultado<16)					   $res="Pobre";
				break;
				
				case $edad >=20 && $edad<=29:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=23 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=19 && $resultado<=22)   $res="Bueno";	
					if($res=="" && $resultado>=13 && $resultado<=18)   $res="Promedio";
					if($res=="" && $resultado<13)					   $res="Pobre";
				break;
					
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=22 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=16 && $resultado<=21)   $res="Bueno";	
					if($res=="" && $resultado>=11 && $resultado<=15)   $res="Promedio";
					if($res=="" && $resultado<11)					   $res="Pobre";

				break;
				
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=21 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=13 && $resultado<=20)   $res="Bueno";	
					if($res=="" && $resultado>=6  && $resultado<=12)   $res="Promedio";
					if($res=="" && $resultado<6)					   $res="Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>=25) 					   $res="Atleta";
					if($res=="" && $resultado>=16 && $resultado<=24)   $res="Excelente";	
					if($res=="" && $resultado>=9  && $resultado<=15)   $res="Bueno";	
					if($res=="" && $resultado>=4  && $resultado<=8)    $res="Promedio";
					if($res=="" && $resultado<4)					   $res="Pobre";
				break;
				
				case $edad >=60 && $edad<=69:
					if($res=="" && $resultado>=18) 					   $res="Atleta";
					if($res=="" && $resultado>=11 && $resultado<=17)   $res="Excelente";	
					if($res=="" && $resultado>=6  && $resultado<=10)   $res="Bueno";	
					if($res=="" && $resultado>=2  && $resultado<=5)    $res="Promedio";
					if($res=="" && $resultado<2)					   $res="Pobre";
				break;
				
			}//switch
			$condicion=$res;
			return $condicion;
	}// evaluación masculina resistencia
	
	function AgregarResultadosYDevolverInformacionResistencia($id_instructor,$id_Cliente,$Condicion,$repeticiones)
	{
		$agregar=new Agregar();
		$consultar=new Consultar();
		//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			
			//Tomando los valores para guardar el resultado de la prueba en la BD
			$TipoPrueba		  = 7;
			$DescPrueba		  = "Resistencia";
			$porcentajeActual = PorcentajeAcordeResultadoFuerza($Condicion);

			//Guardando los resultados de la prueba
			$resultado=$agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Condicion, $porcentajeActual,$repeticiones);
			
			//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
			$resultadoPruebas=$consultar->_ConsultarResultadosPruebas(7,$id_Cliente);
			
			//Creando un array con los resultados de las pruebas
			$arrayResultados=array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba = "Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha=($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje = ($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado	= $fila['Resultado'];
				$Prueba		= array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}
			//print_r($arrayResultados);
			
			//Tomando el consejo de la evaluación física
			$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(7,$Condicion); //El número es el tipo de prueba
			$filaConsejo   = $resultConsejo->fetch_assoc();
			$consejo	   = $filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson=array("Condicion"=>$Condicion, "Resultado"=>$repeticiones, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo, "Porcentaje"=>$porcentajeActual);
			return $salidaJson;		
	}
	
	//Flexibilidad//
	function Flexibilidad($id_Cliente,$Flexibilidad, $id_instructor)
	{
				//Buscando los datos del cliente
		$consultar = new Consultar();
		
		
		$result = $consultar->_ConsultarClientesPorId($id_Cliente);
		$fila	= $result->fetch_assoc();
		
		//tomando sexo y edad
		$edad = $fila['num_edad'];
		$sexo = $fila['de_genero'];
		if($sexo=="MASCULINO")	
		{
			$Condicion  = EvaluacionMasculinaFlexibilidad($edad,$Flexibilidad); //resultado de la prueba
			$salidaJson = AgregarResultadosYDevolverInformacionFlexibilidad($id_instructor,$id_Cliente,$Condicion,$Flexibilidad);
			return $salidaJson;
		}//if
		else
		{
			$Condicion = EvaluacionFemeninaFlexibilidad($edad,$Flexibilidad); //resultado de la prueba
			//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			$salidaJson = AgregarResultadosYDevolverInformacionFlexibilidad($id_instructor,$id_Cliente,$Condicion,$Flexibilidad); 
			return $salidaJson;
		}
	}//Flexibilidad
	
	function  EvaluacionMasculinaFlexibilidad($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=15 && $edad<=19:
					if($res=="" && $resultado>38) 					   $res = "Atleta";
					if($res=="" && $resultado>=34 && $resultado<=38)   $res = "Excelente";	
					if($res=="" && $resultado>=29 && $resultado<=33)   $res = "Bueno";	
					if($res=="" && $resultado>=24 && $resultado<=28)   $res = "Promedio";
					if($res=="" && $resultado<24)					   $res = "Pobre";
				break;
				
				case $edad >=20 && $edad<=29:
					if($res=="" && $resultado>39) 					   	   $res = "Atleta";
					if($res=="" && $resultado>=34 && $resultado<=39)   	   $res = "Excelente";	
					if($res=="" && $resultado>=30 && $resultado<=33)   	   $res = "Bueno";	
					if($res=="" && $resultado>=25 && $resultado<=29)   	   $res = "Promedio";
					if($res=="" && $resultado<25)					   	   $res = "Pobre";
				break;
					
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>37) 					   	   $res = "Atleta";
					if($res=="" && $resultado>=33 && $resultado<=37)       $res = "Excelente";	
					if($res=="" && $resultado>=28 && $resultado<=32)       $res = "Bueno";	
					if($res=="" && $resultado>=23 && $resultado<=27)       $res = "Promedio";
					if($res=="" && $resultado<23)					       $res = "Pobre";

				break;
				
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>34) 					       $res = "Atleta";
					if($res=="" && $resultado>=29 && $resultado<=34)       $res = "Excelente";	
					if($res=="" && $resultado>=24 && $resultado<=28)       $res = "Bueno";	
					if($res=="" && $resultado>=18 && $resultado<=23)       $res = "Promedio";
					if($res=="" && $resultado<18)					       $res = "Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>34) 					       $res = "Atleta";
					if($res=="" && $resultado>=28 && $resultado<=34)       $res = "Excelente";	
					if($res=="" && $resultado>=24  && $resultado<=27)      $res = "Bueno";	
					if($res=="" && $resultado>=16  && $resultado<=23)      $res = "Promedio";
					if($res=="" && $resultado<16)					       $res = "Pobre";
				break;
				
				case $edad >=60 && $edad<=69:
					if($res=="" && $resultado>32) 					       $res = "Atleta";
					if($res=="" && $resultado>=25 && $resultado<=32)       $res = "Excelente";	
					if($res=="" && $resultado>=20 && $resultado<=24)   	   $res = "Bueno";	
					if($res=="" && $resultado>=15 && $resultado<=19)       $res = "Promedio";
					if($res=="" && $resultado<15)					       $res = "Pobre";
				break;
				
			}//switch
			$condicion = $res;
			return $condicion;
	}// evaluación masculina resistencia
	
	function  EvaluacionFemeninaFlexibilidad($edad, $resultado)
	{
		$res="";
		switch($edad)
			{
				//se declara res vacío, si cae en alguno de los rangos de edad, se debe verificar que 
				//resultado fue, si no es se asigna cadena vacía y se sigue buscando.
								
				//case de las edades
				case $edad >=15 && $edad<=19:
					if($res=="" && $resultado>42) 					  $res = "Atleta";
					if($res=="" && $resultado>=38 && $resultado<=42)  $res = "Excelente";	
					if($res=="" && $resultado>=34 && $resultado<=37)  $res = "Bueno";	
					if($res=="" && $resultado>=29 && $resultado<=33)  $res = "Promedio";
					if($res=="" && $resultado<29)					  $res = "Pobre";
				break;
				
				case $edad >=20 && $edad<=29:
					if($res=="" && $resultado>40) 					  $res = "Atleta";
					if($res=="" && $resultado>=37 && $resultado<=40)  $res = "Excelente";	
					if($res=="" && $resultado>=33 && $resultado<=36)  $res = "Bueno";	
					if($res=="" && $resultado>=28 && $resultado<=32)  $res = "Promedio";
					if($res=="" && $resultado<28)					  $res = "Pobre";
				break;
					
				case $edad >=30 && $edad<=39:
					if($res=="" && $resultado>40) 					   $res="Atleta";
					if($res=="" && $resultado>=36 && $resultado<=40)   $res="Excelente";	
					if($res=="" && $resultado>=32 && $resultado<=35)   $res="Bueno";	
					if($res=="" && $resultado>=27 && $resultado<=31)   $res="Promedio";
					if($res=="" && $resultado<27)					   $res="Pobre";

				break;
				
				case $edad >=40 && $edad<=49:
					if($res=="" && $resultado>37) 					   $res="Atleta";
					if($res=="" && $resultado>=34 && $resultado<=37)   $res="Excelente";	
					if($res=="" && $resultado>=30 && $resultado<=33)   $res="Bueno";	
					if($res=="" && $resultado>=25 && $resultado<=29)   $res="Promedio";
					if($res=="" && $resultado<25)					   $res="Pobre";
				break;
				
				case $edad >=50 && $edad<=59:
					if($res=="" && $resultado>38) 					   $res="Atleta";
					if($res=="" && $resultado>=33  && $resultado<=36)   $res="Excelente";	
					if($res=="" && $resultado>=30  && $resultado<=32)   $res="Bueno";	
					if($res=="" && $resultado>=25  && $resultado<=29)   $res="Promedio";
					if($res=="" && $resultado<25)					   $res="Pobre";
				break;
				
				case $edad >=60 && $edad<=69:
					if($res=="" && $resultado>34) 					   $res="Atleta";
					if($res=="" && $resultado>=31 && $resultado<=34)   $res="Excelente";	
					if($res=="" && $resultado>=27 && $resultado<=30)   $res="Bueno";	
					if($res=="" && $resultado>=23 && $resultado<=26)    $res="Promedio";
					if($res=="" && $resultado<23)					   $res="Pobre";
				break;
				
			}//switch
			$condicion=$res;
			return $condicion;
	}// evaluación masculina resistencia
	
	function AgregarResultadosYDevolverInformacionFlexibilidad($id_instructor,$id_Cliente,$Condicion,$repeticiones)
	{
		$agregar   = new Agregar();
		$consultar = new Consultar();
		//En esta línea se agregan el resultado de la prueba, se obtienen los datos necesarios y se devuelve el array para
			//devolver a la pantalla principal.
			
			//Tomando los valores para guardar el resultado de la prueba en la BD
			$TipoPrueba		  = 8;
			$DescPrueba		  = "Flexibilidad";
			$porcentajeActual = PorcentajeAcordeResultadoFuerza($Condicion);
			//Guardando los resultados de la prueba
			$resultado = $agregar->_AgregarResultadoPrueba($id_instructor, $id_Cliente, $TipoPrueba, $DescPrueba, $Condicion, $porcentajeActual,$repeticiones);
			
			//Obteniendo pruebas de los últimos 3 meses para saber el resultado de la persona en las gráficas
			$resultadoPruebas = $consultar->_ConsultarResultadosPruebas(8,$id_Cliente);
			
			//Creando un array con los resultados de las pruebas
			$arrayResultados = array();
			
			//tomando los resultados
			for($i=0; $i<3; $i++)
			{
				$fila=$resultadoPruebas->fetch_assoc();
				$NumPrueba="Prueba".$i;
				//Tomando los valores para asignarlos al array.
				
				//Si los valores vienen null, se les asigna 0 para que al llegar al lado del cliente se asigne otro valor y se cargue la gráfica
				$fecha 		= ($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0; //Devolviendo un string con la fecha con el método de la línea 148
				$porcentaje = ($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
				$resultado  = $fila['Resultado'];
				$Prueba 	= array("Resultado"=>$resultado,"Porcentaje"=>$porcentaje, "Fecha"=>$fecha);	
				array_push ($arrayResultados,$Prueba);
			}
			//print_r($arrayResultados);
			
			//Tomando el consejo de la evaluación física
			$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(6,$Condicion); //El número es el tipo de prueba
			$filaConsejo   = $resultConsejo->fetch_assoc();
			$consejo 	   = $filaConsejo['Consejo'];
			//DEvolviendo parámetros para la notificación				
			$salidaJson = array("Condicion"=>$Condicion, "Resultado"=>$repeticiones, "id_cliente"=>$id_Cliente,"Resultados"=>$arrayResultados,
			"TipoPrueba"=>$TipoPrueba, "Consejo"=>$consejo, "Porcentaje"=>$porcentajeActual);
			return $salidaJson;		
	}
	
	
	function ReportePdf($id_cliente)
	{
		
		$consultar = new Consultar();
		
		//Conseguir información de todas las pruebas
		$result    = $consultar->_ConsultarResultadosPruebasReporte(1,$id_cliente);
		$num_rows  = $result->num_rows;
		$Condicion = array();
		//tomando los resultados
			
		for($i=0; $i<$num_rows; $i++)			
		{
			$fila			    = $result->fetch_assoc();
			$resultado_numerico = $fila['resultado_numerico']; 
			$des_prueba 		= ($fila['Desc_Prueba']!=null)?$fila['Desc_Prueba']:0;
			$Cond	 		    = $fila['Resultado'];
			$fecha 				= ($fila['fecha']!=null)?ConvertirTimeStamp($fila['fecha']):0;
			$Porcentaje			= ($fila['Porcentaje']!=null)?$fila['Porcentaje']:0;
			
			//Tomando el consejo de la evaluación física
			$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(1,$Cond);
			$filaConsejo   = $resultConsejo->fetch_assoc();
			$consejo	   = $filaConsejo['Consejo'];
			
			//Obteniendo tamaño de la barra de progreso.
			switch($Cond)
					{
						case 'Atleta':
							$Barra    = "progress-bar-success";
							$longitud = '"width","100%"';
						break;
						
						case 'Excelente':
							$Barra    ="progress-bar-success";
							$longitud = '"width","80%"';
						break;
						
						case 'Bueno':
							$longitud = '"width","60%"';
						break;
						
						case 'Promedio':
							$Barra    ="progress-bar-warning";
							$longitud = '"width","40%"';
						break;
						
						case 'Pobre':
							$Barra    ="progress-bar-danger";
							$longitud = '"width","20%"';
						break;
					}//switch
			
			$Con   				= array("resultado_numerico"=>$resultado_numerico,"des_prueba"=>$des_prueba,"Condicion"=>$Cond,
			"fecha"=>$fecha,"Porcentaje"=>$Porcentaje,"Consejo"=>$consejo,"Barra"=>$Barra, "Longitud"=>$longitud);
			array_push($Condicion,$Con);
		}//for	
		
		/*$resultConsejo = $consultar->_ConsultarConsejoAcordeResultado(1,$Condicion);
		$filaConsejo   = $resultConsejo->fetch_assoc();
		$consejo	   = $filaConsejo['Consejo'];*/
		
		$salidaJson = array("Condicion"=>$Condicion);		
		return $salidaJson;
	}//ReportePdf
?>