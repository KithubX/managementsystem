<?php 
	class Consultar
	{
		//Querys de Usuarios

		function _LoginUsuario($usuario,$pass)
		{
			R::begin();
			    try{
			       $user = R::getRow("SELECT * FROM sgusuarios where nb_usuario=? and pw_password=?",[$usuario,$pass]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $user =  R::rollback();
			    }

			R::close();
			return $user;

		}//_EliminarRaspberry

		function _ConsultarUsuarios()
		{
			R::begin();
			    try{
			       $Usuarios = R::getAll("SELECT * FROM sgusuarios  where sn_activo =1 ORDER BY id ASC");
			        R::commit();
			    }
			    catch(Exception $e) {
			       $user =  R::rollback();
			    }
			R::close();
			return $Usuarios;
		}//_ConsultarUsuarios

		function _ConsultarUsuariosFiltros()
		{
			R::begin();
			    try{
			       $Usuarios = R::getAll("SELECT id,CONCAT(nb_nombre, ' ', nb_apellidos) as nombre FROM sgusuarios  where sn_activo =1 ORDER BY id ASC");
			        R::commit();
			    }
			    catch(Exception $e) {
			       $user =  R::rollback();
			    }
			R::close();
			return $Usuarios;
		}//_ConsultarUsuarios

		function _ConsultarUsuarioPorId($id)
		{
			R::begin();
			    try{
			       $Usuarios = R::getRow('SELECT * FROM sgusuarios  where sn_activo =1 and id=? ORDER BY id ASC',[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $user =  R::rollback();
			    }
			R::close();
			return $Usuarios;
		}
		
		function _ConsultarEjerciciosPorMusculo($id)
		{
			$query = '
			SELECT
			eje.id as id_ejercicio,
			eje.nb_ejercicio,
			mus.nb_musculo,
			mus.id as id_musculo
			from sgejercicios eje
			LEFT JOIN sgmusculos mus
			ON mus.id = eje.id_musculo
			where eje.id_musculo = ? ';
			R::begin();
			    try{
			       $musculos = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $musculos =  R::rollback();
			       $musculos = "Error";
			    }
			R::close();
			return $musculos;
		}//_ConsultarEjerciciosPorMusculo

		//Final de queries de usuarios

		//Queries de Utilidades

		function _ConsultarConsejosPorPrueba($id)
		{
			R::begin();
			    try{
			    	$query = '
			    		SELECT  
			    		con.id,
						con.Resultado,
						con.consejo as Consejo,
						pru.id as "id_prueba",
						pru.nm_prueba
			    		FROM sgconsejos con 
			    		LEFT JOIN sgtipospruebas pru ON con.id_tipo_prueba= pru.id 
			    		where con.id_tipo_prueba= ?  ORDER BY con.id ASC
			    	';
			       $consejos = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $consejos =  R::rollback();
			    }
			R::close();
			return $consejos;
		}

		function _ConsultarConsejoPorId($id)
		{
			R::begin();
			    try{
			    	$query = '
					SELECT
					con.id,
					con.consejo as Consejo,
					con.Resultado,
					pru.nm_prueba
					FROM 
					sgconsejos con
					LEFT JOIN 
					sgtipospruebas pru
					ON con.id_tipo_prueba = pru.id
					where  con.id= ? ORDER BY id ASC
			    	';
			       $Consejo = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $Consejo =  R::rollback();
			    }
			R::close();
			return $Consejo;			
		}

		function _ConsultartiposPruebas()
		{
			R::begin();
			    try{
			       $Pruebas = R::getAll('SELECT * FROM sgtipospruebas  ORDER BY id ASC');
			        R::commit();
			    }
			    catch(Exception $e) {
			       $Pruebas =  R::rollback();
			    }
			R::close();
			return $Pruebas;
		}//_ConsultartiposPruebas

		function _ConsultarMaquinas()
		{
			R::begin();
			    try{
			    	$query = '
					SELECT 
					maq.id,
					maq.desc_maquina,
					maq.nb_maquina,
					maq.num_maquina,
					cat.nb_categoriamaquina
					FROM sgmaquinas maq
					LEFT JOIN sgcategoriamaquina cat
					ON maq.id_CategoriaMaquina = cat.id
					where maq.sn_activo =1 ORDER BY id ASC
			    	';
			       $Maquinas = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $Maquinas =  R::rollback();
			    }
			R::close();
			return $Maquinas;
		}//_ConsultarMaquinas

		function _BuscarMaquinaId($id)
		{
			R::begin();
			    try{
			    	$query = 'SELECT * FROM sgmaquinas where id = ?';
			       $Maquina = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $Maquina =  R::rollback();
			    }
			R::close();
			return $Maquina;
		}//_BuscarMaquinaId

		function _ConsultarTiposMaquina()
		{
			R::begin();
			    try{
			    	$query = '
						SELECT 
						cat.id,
						cat.nb_categoriamaquina,
						cat.desc_categoriamaquina,
						us.nb_nombre,
						us.nb_apellidos
						FROM sgcategoriamaquina  cat
						LEFT JOIN sgusuarios us 
						on us.id = cat.id_usuarioregistro
						where cat.sn_activo=1
			    	';
			       $tipoMaquina = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $tipoMaquina =  R::rollback();
			    }
			R::close();
			return $tipoMaquina;
		}//_ConsultarTiposMaquina

		function _BuscarMaquinaPorCategoria($id)
		{
			$filtro = "";
			if($id != "Todas"){$filtro = "maq.id_categoriamaquina=? and";}	
			R::begin();
			    try{
			    	$query = 'SELECT
			    			  maq.id,
			    			  maq.nb_maquina,
			    			  maq.desc_maquina,
			    			  maq.num_maquina,
			    			  cat.nb_CategoriaMaquina
			    			  FROM sgmaquinas maq
			    			  LEFT JOIN sgcategoriamaquina cat
			    			  on maq.id_categoriamaquina = cat.id
			    			  where '.$filtro.' maq.sn_activo=1 ORDER BY maq.id ASC';
			       $maquinas = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $maquinas =  R::rollback();
			       $maquinas = "error";
			    }
			R::close();
			return $maquinas;
		}//_BuscarMaquinaPorCategoria

		//queries de rutinas
		function _BuscarCategoriaPorId($id)
		{
			R::begin();
			    try{
			    	$query = 'SELECT * from sgcategoriamaquina where id=?';
			       $cat = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $cat =  R::rollback();
			       $cat = "error";
			    }
			R::close();
			return $cat;
		}//_BuscarCategoriaPorId

		function _ConsultarTiposRutina()
		{
			R::begin();
			    try{
			    	$query = '
						SELECT 
						tipru.id,
						tipru.nb_tiporutina,
						tipru.desc_tiporutina,
						usu.nb_nombre,
						usu.nb_apellidos
						from sgtiposrutina tipru
						LEFT JOIN sgusuarios usu
						ON tipru.id_usuarioregistro = usu.id
						where tipru.sn_activo=1
			    	';
			       $cat = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $cat =  R::rollback();
			       $cat = "error";
			    }
			R::close();
			return $cat;
		}//_ConsultarTiposRutina

		function _ConsultarEdades()
		{
			R::begin();
			    try{
			    	$query = '
						SELECT * from sgedad;
			    	';
			       $cat = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $cat =  R::rollback();
			       $cat = "error";
			    }
			R::close();
			return $cat;
		}//_ConsultarEdades

		function _ConsultarTipoRutinaPorId($id)
		{
			R::begin();
			    try{
			    	$query = 'SELECT * from sgtiposrutina where id=?';
			       $tipru = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $tipru =  R::rollback();
			       $tipru = "error";
			    }
			R::close();
			return $tipru;
		}//_ConsultarTipoRutinaPorId

		function _ConsultarMusculos()
		{
			$query='
			select  
			Mus.id,
			Mus.nb_musculo,
			Mus.desc_musculo,
			us.nb_nombre,
			us.nb_apellidos,
			Rut.nb_tiporutina
			from 
			sgmusculos Mus
			LEFT JOIN sgusuarios us
			on Mus.id_usuario_creacion=us.id
			LEFT JOIN sgtiposrutina Rut
			ON Rut.id=Mus.id_tiporutina
			WHERE Mus.sn_activo=1';
			R::begin();
			    try{
			       $musculos = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $musculos =  R::rollback();
			       $musculos = "error";
			    }
			R::close();
			return $musculos;
		}//_ConsultarMusculos

		function _ConsultarMusculosPorId($id)
		{
			$query='
			select  
			Mus.id,
			Mus.nb_musculo,
			Mus.desc_musculo,
			Mus.id_tiporutina,
			us.nb_nombre,
			us.nb_apellidos,
			Rut.nb_tiporutina
			from 
			sgmusculos Mus
			INNER JOIN sgusuarios us
			on Mus.id_usuario_creacion=us.id
			INNER JOIN sgtiposrutina Rut
			ON Rut.id=Mus.id_tiporutina
			WHERE Mus.sn_activo=1 AND Mus.id= ? 
			';	
			R::begin();
			    try{
			       $musculo = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $musculo =  R::rollback();
			       $musculo = "error";
			    }
			R::close();
			return $musculo;
		}//_ConsultarMusculosPorId

		function _ConsultarEjercicios()
		{
			$query='
			select 
			Ej.id,
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Ej.id_tiporutina,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Tip.nb_tiporutina,
			MA.nb_maquina
			FROM sgejercicios Ej
			LEFT JOIN sgusuarios Us
			ON Us.id=Ej.id_UsuarioCreacion
			LEFT JOIN sgmusculos Mus
			ON Mus.id= Ej.id_musculo
			LEFT JOIN sgtiposrutina Tip
			ON Tip.id=Ej.id_TipoRutina
			LEFT JOIN sgmaquinas MA
			ON Ej.id_maquina=MA.id
			WHERE  Ej.sn_activo=1
		';
		R::begin();
			    try{
			       $ejercicios = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $ejercicios =  R::rollback();
			       $ejercicios = "error";
			    }
			R::close();
			return $ejercicios;
		}//_ConsultarEjercicios

		function _ConsultarTiposRutinas()
		{
			$query = 'select * from sgtiposrutina where sn_activo = 1';
			R::begin();
			    try{
			       $ejercicios = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $ejercicios =  R::rollback();
			       $ejercicios = "error";
			    }
			R::close();
			return $ejercicios;
		}//_ConsultarTiposRutinas

		function _ConsultarMusculosPorRutina($id)
		{
			$query='
			select  
			Mus.id,
			Mus.nb_musculo,
			Mus.desc_musculo,
			us.nb_nombre,
			us.nb_apellidos,
			Rut.nb_TipoRutina
			from 
			sgmusculos Mus
			INNER JOIN sgusuarios us
			on Mus.id_usuario_creacion=us.id
			INNER JOIN sgtiposrutina Rut
			ON Rut.id=Mus.id_tiporutina
			WHERE Mus.sn_activo=1
			AND Rut.id= ?
			';	
			R::begin();
			    try{
			       $musculos = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $musculos =  R::rollback();
			       $musculos = "error";
			    }
			R::close();
			return $musculos;
		}//_ConsultarMusculos

		function _ConsultarEjercicioPorId($id)
		{
			$query='
			select 
			Ej.id,
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Mus.id as "id_musculo",
			Tip.nb_TipoRutina,
			Tip.id as "id_tiporutina",
			Maq.id as "id_maquina",
			CatMaq.id as "id_categoriamaquina",
			CatMaq.nb_CategoriaMaquina
			FROM sgejercicios Ej
			LEFT JOIN sgusuarios Us
			ON Us.id=Ej.id_UsuarioCreacion
			LEFT JOIN sgmusculos Mus
			ON Mus.id= Ej.id_musculo
			LEFT JOIN sgtiposrutina Tip
			ON Tip.id=Ej.id_tiporutina
			LEFT JOIN sgmaquinas Maq
			on Maq.id=Ej.id_maquina
			LEFT JOIN sgcategoriamaquina CatMaq
			on CatMaq.id = Maq.id_CategoriaMaquina
			WHERE Ej.id= ?
			';
			$respuesta = $this->EjecutarTransaccionSinglerow($query,$id);
			return $respuesta;
		}//_ConsultarEjercicioPorId

		function EjecutarTransaccionSinglerow($query,$id)
		{
			R::freeze(1);
			R::begin();
			    try{
			       $objeto = R::getRow($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $objeto =  R::rollback();
			       $objeto = "Error";
			    }
			R::close();
			return $objeto;
		}

		function EjecutarTransaccionSinglerowDoubleParam($query,$param1,$param2)
		{
			R::freeze(1);
			R::begin();
			    try{
			       $objeto = R::getRow($query,[$param1,$param2]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $objeto =  R::rollback();
			       $objeto = "Error";
			    }
			R::close();
			return $objeto;
		}

		function EjecutarTransaccionAll($query,$id)
		{
			
			R::begin();
			    try{
			       $objeto = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $objeto =  R::rollback();
			       $objeto = "Error";
			    }
			R::close();
			return $objeto;
		}

		function EjecutarTransaccionDep($query)
		{
			$clientes = "";
			$error = 0;
			$msj   = "";
			R::begin();
			    try{
			       $clientes = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $msj   = $e->getMessage();
			       $error = 1;
			       $clientes =  R::rollback();
			    }
			R::close();
			$datos = array("clientes"=>$clientes,"error"=>$error,"msj"=>$msj);
			return $datos;
		}

		function EjecutarTransaccionAll3Params($query,$param1,$param2,$param3)
		{
			
			R::begin();
			    try{
			       $objeto = R::getAll($query,[$param1,$param2,$param3]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $objeto =  R::rollback();
			       $objeto = "Error";
			    }
			R::close();
			return $objeto;
		}

		function EjecutarTransaccionAllNoParams($query)
		{
			
			R::begin();
			    try{
			       $objeto = R::getAll($query);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $objeto =  R::rollback();
			       $objeto = "Error";
			    }
			R::close();
			return $objeto;
		}


		function _ConsultarEjerciciosPorTipoRutina($id)
	{
		$condicion = ($id!="Todos")?"Tip.id=? AND":"";
		$query='
			select 
			Ej.id,
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Ej.id_tiporutina,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Tip.nb_tiporutina,
			MA.nb_maquina
			FROM sgejercicios Ej
			LEFT JOIN sgusuarios Us
			ON Us.id=Ej.id_usuariocreacion
			LEFT JOIN sgmusculos Mus
			ON Mus.id= Ej.id_musculo
			LEFT JOIN sgtiposrutina Tip
			ON Tip.id=Ej.id_TipoRutina
			LEFT JOIN sgmaquinas MA
			ON Ej.id_maquina=MA.id
			WHERE '.$condicion.' Ej.sn_activo=1
		';
		R::begin();
			    try{
			    	if($id!="Todos")
			    	{
			    		$respuesta = R::getAll($query,[$id]);
			        	R::commit();
			    	}
			    	else
			    	{
			    		$respuesta = R::getAll($query);
			        	R::commit();
			    	}//else
			    }
			    catch(Exception $e) {
			       $respuesta =  R::rollback();
			       $respuesta = "Error";
			    }
			R::close();
		return $respuesta;
	}//_ConsultarEjerciciosPorId

		function _ConsultarMusculosPorTipoRutinaId($id)
		{
			$query = '
				SELECT
				Mus.id,
				Mus.nb_musculo,
				Tip.nb_tiporutina
				FROM sgmusculos Mus
				INNER JOIN sgtiposrutina Tip on Mus.id_tiporutina = Tip.id
				WHERE Tip.id =? and Mus.sn_activo=1
			';
		    R::begin();
			    try{
			       $musculos = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $musculos =  R::rollback();
			       $musculos = "error";
			    }
			R::close();
			return $musculos;
		}//_ConsultarMusculosPorTipoRutinaId

		function _ConsultarCuerposPorNombre($name)
		{
			$query     = 'select * from sgtipocuerpo where nb_cuerpo = ?';
			$respuesta = $this->EjecutarTransaccionSinglerow($query,$name);
			return $respuesta;
		}//_ConsultarCuerposPorNombre



		function _ConsultarEjerciciosDeRutinas($id)
		{
			$query = '
			select DISTINCT
			eje.nb_ejercicio,
			eje.desc_ejercicio,
			rut.nb_rutina,
			rut.desc_rutina,
			ejeru.id_ejercicio

			from sgrutinas rut

			LEFT JOIN sgejerciciosrutina ejeru
			ON  rut.id = ejeru.id_rutina

			LEFT JOIN sgejercicios eje
			ON eje.id  = ejeru.id_ejercicio

			WHERE ejeru.id_ejercicio =?  AND ejeru.sn_activo = 1 AND rut.sn_activo=1
			';
			$respuesta = $this->EjecutarTransaccionAll($query,$id);
			return $respuesta;
		}//_ConsultarEjerciciosDeRutinas


		//Querys de clientes
		function _ConsultarClientes()
		{
			$query='
				SELECT
				clientes.id,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido" 
				FROM sgclientes clientes
				left join sgusuarios  usuarios on clientes.id_usuario_registro=usuarios.id
				where clientes.sn_activo=1
				ORDER BY clientes.id ASC
			
			';
			$clientes = $this->EjecutarTransaccionAllNoParams($query);
			return $clientes;
		}//_ConsultarClientes

		function _ConsultarclientePorId($id)
		{
			$query = 'SELECT 
			cliente.id,
			cliente.nb_cliente,
			cliente.nb_apellidos,
			cliente.de_genero,
			cliente.de_email,
			cliente.num_telefono,
			cliente.num_celular,
			cliente.de_colonia,
			cliente.de_domicilio,
			cliente.num_codigopostal,
			cuerpo.id as id_cuerpo,
			cuerpo.nb_cuerpo,
			SPLIT_STR(fh_nacimiento, "-", 1) as birth_year,
			SPLIT_STR(fh_nacimiento, "-", 2) as birth_month,
			SPLIT_STR(fh_nacimiento, "-", 3) as birth_day
			FROM sgclientes cliente
			LEFT JOIN sgtipocuerpo cuerpo
			ON cliente.id_tipocuerpo = cuerpo.id
			where cliente.id= ?';
			$respuesta = $this->EjecutarTransaccionSinglerow($query,$id);
			return $respuesta;
		}//_ConsultarclientePorId

		function _ConsultarClientesPorEntrenador($id)
		{
			$condicion = ($id!="Todos")?"AND clientes.id_usuario_registro = ?":"";

			$query = '
				SELECT
				clientes.id,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido" 
				FROM sgclientes clientes
				left join sgusuarios  usuarios on clientes.id_usuario_registro=usuarios.id
				where clientes.sn_activo=1 '.$condicion.'
				ORDER BY clientes.id ASC
			';
			R::begin();
			    try{
			       $respuesta = R::getAll($query,[$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $respuesta =  R::rollback();
			       $respuesta = "Error";
			    }
			R::close();
			$respuesta = $this->EjecutarTransaccionAll($query,$id);
			return $respuesta;
		}///_ConsultarClientesPorEntrenador

		function _ConsultarInformacionClienteReporteFormulario($id)
	{
		$query=' 
			select 	DISTINCT
			Clie.id,
			Clie.nb_cliente,
			Clie.nb_apellidos,
			/* información de sg_formulario*/
			Form.id as id_form,
			cantidad_cigarros,
			desayuno_diario,
			comida_diaria,
			cena_diaria,
			entrecomida_diaria,
			frecuencia_entrecomida,
			plan_alimenticio,
			intensidad_ejercicio,
			intensidad_ejercicio2,
			intensidad_ejercicio3,
			intensidad_ejercicio4,
			intensidad_ejercicio5,
			actividades_deseables,
			actividades_indeseables,
			deporte_frecuente,
			minutos_dia,
			dias_semana,
			resultado_ejercicio,
			condicion_cardiaca,
			condicion_pecho,
			condicion_pechoreciente,
			condicion_balance,
			lesion_fisica,
			medicamentos_corazon,
			impedimento_entrenamiento,
			lecturas_anormales,
			cirujia_bypass,
			dificultad_respirar,
			enfermedades_renales,
			arritmia,
			colesterol,
			presion_alta,
			molestias_articulaciones,
			molestias_espalda,
			programa_ejercicio
			
			FROM sgformulario Form
			INNER JOIN sgclientes Clie
			ON Clie.id=Form.id_cliente where Form.id_cliente = ? 	
		';	
		$respuesta = $this->EjecutarTransaccionSinglerow($query,$id);
		return $respuesta;
	}//_ConsultarInformacionClienteReporteFormulario

		function _ConsultarSiClienteHizoElFormulario($id)
		{
			$query='
				select * from sg_formulario where id_cliente = ?
			';	
			$respuesta = $this->EjecutarTransaccionSinglerow($query,$id);
			return $respuesta;
		}//_ConsultarSiClienteHizoElFormulario

		function _ConsultarInformacionClientesRutinaPorIdCliente($id_cliente)
	{
		$query='SELECT
		Form.dias_semana,
		Form.minutos_dia,
		Form.resultado_ejercicio,
		clie.nb_cliente,
		clie.nb_apellidos
		
		FROM
		sgformulario Form
		
		INNER JOIN sgclientes clie
		on Form.id_cliente=clie.id

		WHERE clie.id=?';
		R::begin();
			    try{
			       $respuesta = R::getRow($query,[$id_cliente]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $respuesta =  R::rollback();
			       $respuesta = "Error";
			    }
		return $respuesta;;
	}//_ConsultarExistenciaRutinasParaUsuarioPorId

	function _ConsultarRutinasClientesPorIdCliente($id_cliente)
	{
		$query='
			select * from sgrutinasclientes where id_cliente =? and sn_activo=1
		';
		R::begin();
			    try{
			       $respuesta = R::getRow($query,[$id_cliente]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $respuesta =  R::rollback();
			       $respuesta = "Error";
			    }
		return $respuesta;;
	}//_ConsultarRutinasClientesPorIdCliente

		//Queries de rutinas
		function _ConsultarRutinasTotales()
	{
		
		//Este querie devuelve TODAS las rutinas ordenadas de principiante hasta avanzado.
		$query = '
		SELECT
		Rut.id as id_rutina,
		Rut.nb_rutina,
		Rut.desc_rutina,
		Rut.fh_creacion,
		Usu.nb_nombre,
		Usu.nb_apellidos,
		Cat.nb_categoriarutina,
		Gen.id as id_genero,
		Gen.nb_tiporutina,
		cuerpo.id as id_cuerpo,
		cuerpo.nb_cuerpo,
		edad.nb_edad
		FROM sgrutinas Rut
		left JOIN sgusuarios Usu
		ON Usu.id=Rut.id_usuariocreacion
		left JOIN sgcategoriasrutina Cat
		ON Cat.id=Rut.id_categoriarutina
		LEFT JOIN sggenerosrutina Gen
		ON Gen.id= Rut.id_generorutina
		LEFT JOIN sgtipocuerpo cuerpo
		ON cuerpo.id = Rut.id_tipocuerpo
		LEFT JOIN sgedad edad
		ON edad.id = Rut.id_edad
		where  Rut.sn_activo=1   order by id_rutina asc
		';	
		$rutinas = $this->EjecutarTransaccionAllNoParams($query);
		return $rutinas;
	}//_ConsultarRutinasTotales

	function _ConsultarCategoriaRutinas()
	{
		$query='
			SELECT DISTINCT
			id,
			nb_categoriarutina
			from sgcategoriasrutina
			ORDER BY id
		';	
		$rutinas = $this->EjecutarTransaccionAllNoParams($query);
		return $rutinas;
	}//_ConsultarCategoriaRutinas

	function _ConsultarGenerosRutina()
	{
		//En esta función se buscan los geéneros de las rutinas.
		$query='
			select id,nb_tiporutina from sggenerosrutina
		';
		$generos = $this->EjecutarTransaccionAllNoParams($query);
		return $generos;
	}//_ConsultarGenerosRutina

	function _ConsultarEntrenadoresConRutinas()
	{
		$query="
			SELECT distinct
			Usu.id as id,
			CONCAT(Usu.nb_nombre, ' ',Usu.nb_apellidos) as nombre
			FROM sgrutinas Rut

			LEFT  JOIN sgusuarios Usu
			ON Rut.id_usuariocreacion=Usu.id
			WHERE Rut.sn_activo = 1
			ORDER BY Usu.id
		";	
		$entrenadores = $this->EjecutarTransaccionAllNoParams($query);
		return $entrenadores;
	}//_ConsultarEntrenadoresConRutinas

	function _ConsultarCategoriaRutinaPorEntrenador($id)
	{
		$query= '
		select DISTINCT
		cat.id,
		nb_categoriarutina
		from sgcategoriasrutina cat

		LEFT JOIN sgrutinas rut ON
		rut.id_categoriarutina = cat.id

		LEFT JOIN sgusuarios usu ON
		rut.id_usuariocreacion = usu.id

		where usu.id=? AND rut.sn_activo = 1
		';
		$categorias = $this->EjecutarTransaccionAll($query,$id);
		return $categorias;
	}//_ConsultarCategoriaRutinaPorEntrenador

	function _ConsultarGenerosRutinaPorEntrenador($id)
	{
		$query = '
				SELECT DISTINCT
				Gen.nb_tiporutina,
				Gen.id

				FROM sggenerosrutina Gen

				left JOIN sgrutinas Rut
				on Rut.id_generorutina = Gen.id

				WHERE Rut.id_usuariocreacion=1

				ORDER BY Gen.id
		';	
		//echo $query;
		$generos = $this->EjecutarTransaccionAll($query,$id);
		return $generos;
	}//_ConsultarGenerosRutinaPorEntrenador

	function _ConsultarRutinasFiltradas($entrenador,$tipo_rutina,$genero,$edad,$cuerpo)
	{
		//definiendo las condiciones			
		$condicionent = ($entrenador!="Todos")?"AND Rut.id_usuariocreacion =".$entrenador." ":"";
		$condiciontip = ($tipo_rutina!="Todos")?"AND Rut.id_categoriarutina =".$tipo_rutina." ":"";
		$condiciongen = ($genero!="Todos")?"AND Rut.id_generorutina =".$genero." ":"";
		$condicioned  = ($edad!="Todos")?"AND Rut.id_edad =".$edad." ":"";
		$condicioncue = ($cuerpo!="Todos")?"AND Rut.id_tipocuerpo =".$cuerpo." ":"";

		//Este querie devuelve TODAS las rutinas ordenadas de principiante hasta avanzado.
		$query = '
		SELECT
		Rut.id as id_rutina,
		Rut.nb_rutina,
		Rut.desc_rutina,
		Rut.fh_creacion,
		Usu.nb_nombre,
		Usu.nb_apellidos,
		Cat.nb_categoriarutina,
		Gen.id as id_genero,
		Gen.nb_tiporutina,
		cuerpo.id as id_cuerpo,
		cuerpo.nb_cuerpo,
		edad.nb_edad
		FROM sgrutinas Rut
		left JOIN sgusuarios Usu
		ON Usu.id=Rut.id_usuariocreacion
		left JOIN sgcategoriasrutina Cat
		ON Cat.id=Rut.id_categoriarutina
		LEFT JOIN sggenerosrutina Gen
		ON Gen.id= Rut.id_generorutina
		LEFT JOIN sgtipocuerpo cuerpo
		ON cuerpo.id = Rut.id_tipocuerpo
		LEFT JOIN sgedad edad
		ON edad.id = Rut.id_edad
		where  Rut.sn_activo=1   
		'.$condicionent.'
		'.$condiciontip.'
		'.$condiciongen.'
		'.$condicioned.'
		'.$condicioncue.'
		order by id_rutina asc
		';
		R::freeze(1);	
		R::begin();
	    try{
	       $rutinas = R::getAll($query);
	        R::commit();
	    }
	    catch(Exception $e) {
	       $rutinas =  R::rollback();
	       $rutinas = "Error";
	    }
	R::close();
	return $rutinas;
	}//_ConsultarRutinasFiltradas

	function _ConsultarTiposCuerpo()
	{
		$query='SELECT id,nb_cuerpo from sgtipocuerpo';
		$cuerpos = $this->EjecutarTransaccionAllNoParams($query);
		return $cuerpos;
	}//_ConsultarTiposCuerpo
	
	function _ConsultarTiposDeRutina()
	{
		$query="
			SELECT
			TR.id,
			TR.nb_tiporutina,
			TR.desc_tiporutina,
			US.nb_nombre,
			US.nb_apellidos
			from sgtiposrutina TR
			LEFT JOIN sgusuarios US
			ON US.id=TR.id_UsuarioRegistro
			WHERE TR.sn_activo=1 and TR.nb_tiporutina !='Varios'
		";	
		$tipos_rut = $this->EjecutarTransaccionAllNoParams($query);
		return $tipos_rut;
	}//_ConsultarTiposDeRutina

	function _ConsultarPosicionEjercicioRutina($id_rutina)
	{
		 $query = '
			select IFNULL(MAX(id_posicionejercicio+1),1) as "id_posicionejercicio" 
			from sgejerciciosrutina where id_rutina = ?
		';
		$posicion = $this->EjecutarTransaccionSinglerow($query,$id_rutina);
		return $posicion;
	}//_ConsultarPosicionEjercicioRutina

	function _ConsultarPosicionEjercicioRutinaCliente($id_rutina)
	{
		 $query = '
			select IFNULL(MAX(id_posicionejercicio+1),1) as "id_posicionejercicio" 
			from sgejerciciosrutinacliente where id_rutina = ?
		';
		$posicion = $this->EjecutarTransaccionSinglerow($query,$id_rutina);
		return $posicion;
	}//_ConsultarPosicionEjercicioRutina

	function _ConsultarPosicionEjercicioRutinaEdit($id_rutina,$id_dia)
	{
		 $query = '
			select IFNULL(MAX(id_posicionejercicio),0) as "id_posicionejercicio" 
			from sgejerciciosrutina where id_rutina = ? AND id_dia= ?
		';

		R::begin();
			    try{
			       $posicion = R::getRow($query,[$id_rutina,$id_dia]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $posicion =  R::rollback();
			       $posicion = "Error";
			    }
			R::close();
			return $posicion;
	}//_ConsultarPosicionEjercicioRutina

	function _ConsultarPosicionEjercicioRutinaEditDayB($id_rutina,$id_dia)
	{
		 $query = '
			select IFNULL(MAX(id_posicionejercicio),0) as "id_posicionejercicio" 
			from sgejerciciosrutina where id_rutina = ? AND id_dia= ?-1
		';
		R::begin();
			    try{
			       $posicion = R::getRow($query,[$id_rutina,$id_dia]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $posicion =  R::rollback();
			       $posicion = "Error";
			    }
			R::close();
			return $posicion;
	}//_ConsultarPosicionEjercicioRutina

	function _ConsultarInformacionRutinaPreFinalPorId($id)
	{
		$query='
			SELECT 
			/* Datos de la tabla sg_rutinas*/
			Rut.id as id_rutina,
			Rut.id_usuariocreacion,
			Rut.fh_creacion,
			Rut.nb_rutina,
			Rut.desc_rutina,

			/* Datos de Categoría Rutina*/
			CatRu.nb_categoriarutina,

			/* Datos de la tabla sg_ejercicios*/
			Ejer.nb_ejercicio,

			/* Datos de ejercicio sg_ejerciciosrutina*/
			Eje.num_circuitos,
			Eje.num_repeticiones,
			Eje.id as "id_ejercicio",
			Eje.id_dia,
			Eje.id_posicionejercicio,
			Eje.ejercicio_relacion,
			/* Datos de la tabla sg_tiposrutina*/
			TipRu.nb_tiporutina,

			/* DAtos de la tabla días*/
			dias.nb_dia,

			/* Datos de sg_musculos*/
			Musc.nb_musculo,

			/* Datos de la tabla sg_usuarios*/
			Usuarios.nb_nombre,
			Usuarios.nb_apellidos,

			/* Datos de la tabla sg_maquinas */
			Maq.id as id_maquina,
			Maq.nb_maquina

			FROM
			sgrutinas Rut

			/* JOINS*/
			LEFT JOIN 
			sgejerciciosrutina Eje
			ON Eje.id_Rutina

			INNER JOIN sgcategoriasrutina CatRu
			ON CatRu.id=Rut.id_categoriarutina

			INNER JOIN sgejercicios Ejer
			ON Eje.id_Ejercicio=Ejer.id

			INNER JOIN sgtiposrutina TipRu
			ON TipRu.id=Ejer.id_tiporutina

			INNER JOIN sgdias dias
			ON Eje.id_dia=dias.id

			INNER JOIN sgmusculos Musc
			ON Musc.id=Ejer.id_musculo

			INNER JOIN sgusuarios Usuarios ON
			Rut.id_usuariocreacion=Usuarios.id

			LEFT JOIN sgmaquinas Maq ON
			Ejer.id_maquina = Maq.id

			where Rut.id= ? AND Eje.id_rutina= ? AND Eje.sn_activo=1 
			ORDER BY dias.id,Eje.id_posicionejercicio asc, id_ejercicio asc
		';
		R::begin();
			    try{
			       $ejercicios = R::getAll($query,[$id,$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $ejercicios =  R::rollback();
			       $ejercicios = "Error";
			    }

			R::close();
			return $ejercicios;
	}//_ConsultarInformacionRutinaPreFinalPorId

	function _ConsultarId_EjercicioPorId_PosicionEjercicio($id_rut, $id_posicion)
	{
		$query= '
		SELECT 
		Rut.id,
		Rut.id_posicionejercicio,
		Rut.id_dia  
		FROM sgejerciciosrutina  Rut
		where Rut.id_rutina= ? and Rut.id_PosicionEjercicio=? and Rut.sn_activo=1
		';
		R::freeze(1);
		R::begin();
			    try{
			       $info = R::getRow($query,[$id_rut,$id_posicion]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }

			R::close();
			return $info;
	}//_ConsultarId_EjercicioPorId_PosicionEjercicio

	function _ConsultarInformacionPorRutinaYDiaRutinas($id_rutina, $id_dia)
	{
		$query = '
		SELECT 
		/* Datos de la tabla sg_rutinas*/
		Rut.id as id_rutina,
		Rut.id_usuariocreacion,
		Rut.fh_creacion,
		Rut.nb_rutina,
		Rut.desc_rutina,

		/* Datos de Categoría Rutina*/
		CatRu.nb_categoriarutina,

		/* Datos de la tabla sg_ejercicios*/
		Ejer.nb_ejercicio,

		/* Datos de ejercicio sg_ejerciciosrutina*/
		Eje.num_circuitos,
		Eje.num_repeticiones,
		Eje.id as "id_ejercicio",
		Eje.id_dia,
		Eje.id_posicionejercicio,
		/* Datos de la tabla sg_tiposrutina*/
		TipRu.nb_tiporutina,

		/* DAtos de la tabla días*/
		dias.nb_dia,

		/* Datos de sg_musculos*/
		Musc.nb_musculo,

		/* Datos de la tabla sg_usuarios*/
		Usuarios.nb_nombre,
		Usuarios.nb_apellidos

		FROM
		sgrutinas Rut

		/* JOINS*/
		INNER JOIN 
		sgejerciciosrutina Eje
		ON Eje.id_Rutina = Rut.id

		INNER JOIN sgcategoriasrutina CatRu
		ON CatRu.id=Rut.id_categoriarutina

		INNER JOIN sgejercicios Ejer
		ON Eje.id_ejercicio=Ejer.id

		INNER JOIN sgtiposrutina TipRu
		ON TipRu.id=Ejer.id_tiporutina

		INNER JOIN sgdias dias
		ON Eje.id_dia=dias.id

		INNER JOIN sgmusculos Musc
		ON Musc.id=Ejer.id_musculo

		INNER JOIN sgusuarios Usuarios ON
		Rut.id_UsuarioCreacion=Usuarios.id
		where Rut.id= ?  AND Eje.id_rutina=? AND Eje.sn_activo=1 and Eje.id_dia=?
		ORDER BY dias.id,id_posicionejercicio asc, id_ejercicio asc
		';	
		$ejercicios = $this->EjecutarTransaccionAll3Params($query,$id_rutina,$id_rutina,$id_dia);
		return $ejercicios;
	}//_ConsultarInformacionPorRutinaYDia

	function _ConsultarInformacionPorRutinaYDiaRutinasClientes($id_rutina, $id_dia)
	{
		$query = '
			SELECT 
			/* Datos de la tabla sg_rutinas*/
			Rut.id as id_rutina,
			Rut.id_usuariocreacion,
			Rut.fh_creacion,
			Rut.nb_rutina,
			Rut.desc_rutina,

			/* Datos de Categoría Rutina*/
			CatRu.nb_categoriarutina,

			/* Datos de la tabla sg_ejercicios*/
			Ejer.nb_ejercicio,

			/* Datos de ejercicio sg_ejerciciosrutina*/
			Eje.num_circuitos,
			Eje.num_repeticiones,
			Eje.id as "id_ejercicio",
			Eje.id_dia,
			Eje.id_posicionejercicio,
			/* Datos de la tabla sg_tiposrutina*/
			TipRu.nb_tiporutina,

			/* DAtos de la tabla días*/
			dias.nb_dia,

			/* Datos de sg_musculos*/
			Musc.nb_musculo,

			/* Datos de la tabla sg_usuarios*/
			Usuarios.nb_nombre,
			Usuarios.nb_apellidos

			FROM
			sgrutinasclientes Rut

			/* JOINS*/
			LEFT JOIN 
			sgejerciciosrutinacliente Eje
			ON Eje.id_Rutina = Rut.id

			LEFT JOIN sgcategoriasrutina CatRu
			ON CatRu.id=Rut.id_categoriarutina

			LEFT JOIN sgejercicios Ejer
			ON Eje.id_ejercicio=Ejer.id

			LEFT JOIN sgtiposrutina TipRu
			ON TipRu.id=Ejer.id_tiporutina

			LEFT JOIN sgdias dias
			ON Eje.id_dia=dias.id

			LEFT JOIN sgmusculos Musc
			ON Musc.id=Ejer.id_musculo

			LEFT JOIN sgusuarios Usuarios ON
			Rut.id_UsuarioCreacion=Usuarios.id
			where Rut.id= ?  AND Eje.id_rutina=? AND Eje.sn_activo=1 and Eje.id_dia=?
			ORDER BY dias.id,id_posicionejercicio asc, id_ejercicio asc
		';	
		$ejercicios = $this->EjecutarTransaccionAll3Params($query,$id_rutina,$id_rutina,$id_dia);
		return $ejercicios;
	}//_ConsultarInformacionPorRutinaYDia


	function _ConsultarDiasSemana()
	{
		$query='select * from sgdias';
		$dias = $this->EjecutarTransaccionAllNoParams($query);
		return $dias;
	}//_ConsultarDiasSemana

	function _ConsultarInfoTotalEjerciciosPorIdRutina($id_rutina)
	{
		$query='
			select * from sgejerciciosrutina where id_rutina = ? and sn_activo=1 order by id_posicionejercicio
		';
		$ejercicios = $this->EjecutarTransaccionAll($query,$id_rutina);	
		return $ejercicios;
	}

	function _ConsultarExistenciaRegistrosLight($id_cliente)
	{
		$query= 'SELECT * FROM sgpruebaslight where id_cliente =? ';
		$result = $this->EjecutarTransaccionSinglerow($query,$id_cliente);
		return $result;
	}//_ConsultarExistenciaRegistrosLight

	function _ConsultarId_EjercicioClientePorId_PosicionEjercicio($id_Rutina, $id_Posicion)
	{
		$query= '
		SELECT 
		Rut.id,
		Rut.id_posicionejercicio,
		Rut.id_dia 
		FROM sgejerciciosrutinacliente  Rut
		where Rut.id_rutina= ? and Rut.id_posicionejercicio= ? and Rut.sn_activo=1
		';
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getRow($query,[$id_Rutina,$id_Posicion]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;		
	}//_ConsultarId_EjercicioClientePorId_PosicionEjercicio

	function _ConsultarResultadosPruebas($tipo_prueba, $id_cliente)
	{
		$query='
			select * from sgpruebas pruebas 
			where tipo_prueba= ? 
			and id_cliente=?  order by pruebas.fecha DESC limit 3
		';
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query,[$tipo_prueba,$id_cliente]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;
	}

	function _ConsultarResultadosPruebaslight($tipo_prueba, $id_cliente)
	{
		$query='
			select * from sgpruebaslight pruebas 
			where tipo_prueba=?
			and id_cliente=? order by pruebas.fh_creacion DESC limit 3
		';
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query,[$tipo_prueba,$id_cliente]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;	
	}//_ConsultarResultadosPruebaslight

	function _ConsultarResultadosPruebasIMM($tipo_prueba, $id_cliente)
	{
		$query='
			select * from sgpruebaslight pruebas 
			where tipo_prueba=?
			and id_cliente=? order by pruebas.fh_creacion DESC limit 12
		';
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getAll($query,[$tipo_prueba,$id_cliente]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;	
	}//_ConsultarResultadosPruebaslight

	function _ConsultarResultadoPruebaCliente($cliente,$Prueba)
	{
		$query='
			select id,resultado from sgpruebaslight 
			where id_cliente = ? and tipo_prueba = ? 
			order by id desc limit 1
		';
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getRow($query,[$cliente,$Prueba]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;	
	}//_ConsultarResultadoPruebaCliente

	function _ConsultarFechaUltimoBiotestRealizado($id_cliente)
	{
		$query = '
			select MAX(fh_creacion) as "Ultimo_Biotest" from sgpruebaslight where id_cliente = ?
		';
		$result = $this->EjecutarTransaccionSinglerow($query,$id_cliente);
		return $result;
	}//_ConsultarFechaUltimoBiotest

	function _ConsultarUltimoBiotestPruebaLight($id_cliente,$prueba)
	{
		$query = '
			select MAX(fh_creacion) as "Ultimo_Biotest" from sgpruebaslight where id_cliente = ? and tipo_prueba = ?
		';
		$result = $this->EjecutarTransaccionSinglerowDoubleParam($query,$id_cliente,$prueba);
		return $result;
	}

	function _ConsultarUltimoBiotestPrueba($id_cliente,$prueba)
	{
		$query = '
			select MAX(DATE(fecha)) as "Ultimo_Biotest" from sgpruebas where id_cliente = ? and tipo_prueba = ?
		';
		$result = $this->EjecutarTransaccionSinglerowDoubleParam($query,$id_cliente,$prueba);
		return $result;
	}

	function _ConsultarFechaUltimoBiotestUltraRealizado($id_cliente)
	{
		$query = '
			select MAX(DATE(fecha)) as "Ultimo_Biotest" from sgpruebas where id_cliente = ?
		';
		$result = $this->EjecutarTransaccionSinglerow($query,$id_cliente);
		return $result;
	}//_ConsultarFechaUltimoBiotest

	function _ConsultarInformacionClientePorRutinaId($id_rutina)
		{
			$query='
				select 
				/* Datos de sg_rutinasclientes*/
				RUT.id,

				/* Datos de sg_clientes*/
				Clie.nb_cliente,
				Clie.nb_apellidos,
				Clie.de_email,
				Clie.id as id_cliente

				from sgrutinasclientes RUT
				INNER JOIN sgclientes Clie
				ON Clie.id=RUT.id_cliente

				where Rut.id=?
			';	
			$result = $this->EjecutarTransaccionSinglerow($query,$id_rutina);
			return $result;
		}//_ConsultarInformacionClientePorRutinaId
		
		function _ConsultarResultadosPruebasReporte($id_prueba, $id_cliente)
		{
			$query = '
			SELECT distinct
			/* Condición física */
			Con_Fis.resultado_numerico, 
			Con_Fis.desc_prueba, 
			Con_Fis.resultado, 
			Con_Fis.fh_creacion,
			Con_Fis.porcentaje
			/* Condición física*/

			FROM sgpruebaslight Pruebas
			LEFT JOIN 
			(
			select distinct * from sgpruebaslight Prueb 
			where tipo_prueba=?
			and id_cliente=?  order by Prueb.fh_creacion DESC limit 3 
			) Con_Fis ON (Pruebas.id_cliente=Con_Fis.id_cliente)
			where Pruebas.id_cliente = ?  order by fh_creacion desc
			';	
			R::freeze(1);
				R::begin();
				    try{
				       $info = R::getAll($query,[$id_prueba,$id_cliente,$id_cliente]);
				        R::commit();
				    }
				    catch(Exception $e) {
				       $info =  R::rollback();
				       $info = "Error";
				    }
				R::close();
				return $info;	
		}//_ConsultarResultadosPruebasReporte

		function _ConsultarResultadosPruebasReporteUltra($id_prueba, $id_cliente)
		{
			$query = '
			SELECT distinct
			/* Condición física */
			Con_Fis.resultado_numerico, 
			Con_Fis.desc_prueba, 
			Con_Fis.resultado, 
			DATE(Con_Fis.fecha) as fecha,
			Con_Fis.porcentaje
			/* Condición física*/

			FROM sgpruebas Pruebas
			LEFT JOIN 
			(
			select distinct * from sgpruebas Prueb 
			where tipo_prueba=?
			and id_cliente=?  order by Prueb.fecha DESC limit 3 
			) Con_Fis ON (Pruebas.id_cliente=Con_Fis.id_cliente)
			where Pruebas.id_cliente = ?  order by fecha desc
			';	
			R::freeze(1);
				R::begin();
				    try{
				       $info = R::getAll($query,[$id_prueba,$id_cliente,$id_cliente]);
				        R::commit();
				    }
				    catch(Exception $e) {
				       $info =  R::rollback();
				       $info = "Error";
				    }
				R::close();
				return $info;	
		}//_ConsultarResultadosPruebasReporte

	function _ConsultarConsejoAcordeResultado($tipo_prueba, $resultado)
	{
		$query='
			select * from sgconsejos where id_tipo_prueba=? and Resultado=?;
		';	
		R::freeze(1);
			R::begin();
			    try{
			       $info = R::getRow($query,[$tipo_prueba,$resultado]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $info =  R::rollback();
			       $info = "Error";
			    }
			R::close();
			return $info;
	}	

	function _ConsultarClientesPorId($id_Cliente)
		{
			$query='
				SELECT
				clientes.id as id_cliente,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				clientes.de_genero,
				clientes.fh_nacimiento,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido",
			    TIMESTAMPDIFF(YEAR, clientes.fh_nacimiento, CURDATE()) AS num_edad  
				FROM sgclientes clientes
				inner join sgusuarios  usuarios on clientes.id_usuario_registro=usuarios.id
				where clientes.sn_activo=1 and clientes.id=?
			
			';
			$datos = R::getRow($query,[$id_Cliente]);
			return $datos;
		}//_ConsultarClientesPorId

		function _ConsultarClientesFormulario()
		{
			$query='
				SELECT
				clientes.id,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido" 
				FROM sgclientes clientes
				inner join sgusuarios  usuarios on clientes.id_usuario_registro=usuarios.id
				inner join sgformulario form
				on clientes.id=form.id_cliente
				where clientes.sn_activo=1
			
			';
			$datos = $this->EjecutarTransaccionDep($query);
			return $datos;
				
		}
	//queries viejos


		function _ConsultarInformacionUsuarioPorId($id)
		{	
			$query='
				SELECT * FROM sgusuarios where id_usuario="'.$id.'"
			';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarInformacionUsuarioPorId
		
		function _ConsultarUsuarioPorUserName($nb_usuario)
		{
			$query='
				SELECT * FROM sg_usuarios where nb_usuario="'.$nb_usuario.'"
			';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarUsuarioPorUserName
		
		function _ConsultarUsuarioVerificarSiTieneRutinas($id_usuario)
		{
			$query='
				SELECT
				Rut.id_rutina,
				Usu.id_usuario
				FROM sg_rutinas Rut
				
				INNER JOIN sg_usuarios Usu
				ON Rut.id_UsuarioCreacion=Usu.id_usuario
				
				where Usu.id_usuario="'.$id_usuario.'"
			';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarUsuarioVerificarSiTieneRutinas
		
		
		
		function _ConsultarClientesPorInstructor($id_instructor)
		{
			$query='
				SELECT
				clientes.id_cliente,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido" 
				FROM sg_clientes clientes
				inner join sg_usuarios  usuarios on clientes.id_usuario_registro=usuarios.id_usuario
				where clientes.sn_activo=1 AND id_usuario_registro="'.$id_instructor.'"
			 
			';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarClientesPorInstructor
		
		function _ConsultarClientesPorInstructorFormularioHecho($id_instructor)
		{
			$query='
				SELECT
				clientes.id_cliente,
				clientes.nb_cliente,
				clientes.nb_apellidos,
				clientes.de_email,
				clientes.num_celular,
				usuarios.nb_nombre as "Ins_nombre", 
				usuarios.nb_apellidos as "Ins_apellido" 
				FROM sg_clientes clientes
				inner join sg_usuarios  usuarios on clientes.id_usuario_registro=usuarios.id_usuario
				INNER JOIN sg_formulario form on clientes.id_cliente=form.id_cliente
				where clientes.sn_activo=1 AND id_usuario_registro="'.$id_instructor.'"
			 
			';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}//_ConsultarClientesPorInstructor
		
		
		
		
		
		
		function _ConsultarInstructores($id_instructor)
		{
			//Verificando si el id es de un administrador o instructor, si es administrador podrá ver incluso a los administradores en el combo select
			// de el listado de clientes
			/*$consultar	= new Consultar();
			$resultInst = $this->_ConsultarInstructorPorId($id_instructor);
			$fila	    = $resultInst->fetch_assoc();
			$Permiso 	= $fila['Permisos'];*/
			//Permisos een 1 es que es administrador en 0 es instructor
			//$query=($Permiso==1)?"SELECT * from sg_usuarios where sn_activo=1":"SELECT * from sg_usuarios where Permisos=0 and sn_activo=1";
			$query = 'SELECT * from sg_usuarios where sn_activo=1';
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}
		
		function _ConsultarInstructorPorId($id_instructor)
		{
			$query='SELECT * from sg_usuarios where id_usuario="'.$id_instructor.'"';	
			$conectar=new Conectar();
			$con=Conectar::_con();
			$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
			return $result;
		}
		
	function _ConsultarInformacionClientePorId($id)
	{
		$query='
			SELECT *,
			SPLIT_STR(fh_nacimiento, "-", 1) as birth_year,
			SPLIT_STR(fh_nacimiento, "-", 2) as birth_month,
			SPLIT_STR(fh_nacimiento, "-", 3) as birth_day
			FROM sg_clientes cliente
			LEFT JOIN sg_tipocuerpo cuerpo
			ON cliente.id_tipocuerpo = cuerpo.id
			where id_cliente= ?
		';
		$conectar=new Conectar();
		$con=Conectar::_con();
		$result=$con->query($query)or die("Error en $query ".mysqli_error($query));
		return $result;
	}
		
	
	
	//Queries relacionados con el BIOTEST
	
	
		
	
	
	
	function _ConsultarResultadosPruebasIMM1($tipo_prueba, $id_cliente) //consulta el último biotest para imm se necesitan 16 campos.
	{
		$query='
			select * from sg_pruebas  
			where Tipo_Prueba="'.$tipo_prueba.'" 
			and id_cliente="'.$id_cliente.'" order by fecha and id asc limit 16

		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}
	
	
	
	function _ConsultarFechaUltimoBiotest($id_cliente, $tipoPrueba)
	{
		$query='
			select fecha from sg_pruebas pruebas where Tipo_Prueba="'.$tipoPrueba.'" and id_cliente="'.$id_cliente.'" order by pruebas.fecha DESC limit 1
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}
		
	
	
	function _ConsultarBioTestPorIdCliente($id_cliente)
	{
		$query='select * from sg_formulario where id_cliente="'.$id_cliente.'"';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarBioTestPorIdCliente
	
	function _ConsultarTiposPrueba()
	{
		$query='select * from sg_tipospruebas';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}
	
	function _ConsultarInfoPruebasGeneral()
	{
		$query ='
		select *, CASE id_tipo_prueba
		WHEN 1 THEN "Condición Física" 
		when 2 then "Peso"
		when 3 then "Imc"
		when 4 then "Imm"
		when 5 then "Stamina"
		when 6 then "Fuerza"
		when 7 then "Resistencia"
		when 8 then "Flexibilidad"
		end as "Tipo_Prueba"
		from sg_consejos ORDER BY Tipo_Prueba
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInfoPruebasGeneral
	
	function _ConsultarInfoPruebasPorId($id_prueba)
	{
		$query='select *, CASE id_tipo_prueba
		WHEN 1 THEN "Condición Física" 
		when 2 then "Peso"
		when 3 then "Imc"
		when 4 then "Imm"
		when 5 then "Stamina"
		when 6 then "Fuerza"
		when 7 then "Resistencia"
		when 8 then "Flexibilidad"
		end as "Tipo_Prueba"
		from sg_consejos where id_tipo_prueba="'.$id_prueba.'"';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarBioTestPorIdCliente
	
	function _ConsultarInformacionPruebaPorId($id)
	{
		$query='
			select *, CASE id_tipo_prueba
			WHEN 1 THEN "Condición Física" 
			when 2 then "Peso"
			when 3 then "Imc"
			when 4 then "Imm"
			when 5 then "Stamina"
			when 6 then "Fuerza"
			when 7 then "Resistencia"
			when 8 then "Flexibilidad"
			end as "Tipo_Prueba"
			from sg_consejos where id="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInformacionPruebaPorId
	
	function _ConsultarTiposMusculos()
	{
		$query='
			select  
			Ca.id,
			Ca.nb_CategoriaMaquina,
			Ca.Desc_CategoriaMaquina,
			Ca.sn_activo,
			US.nb_nombre,
			US.nb_apellidos
			from sg_categoriamaquina Ca 
			INNER JOIN sg_usuarios US on Ca.Id_UsuarioRegistro=US.id_usuario
			WHERE Ca.sn_activo=1
		';		
		$con    = Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarTiposMusculos
	
	function _ConsultarMusculosPorTipoRutinaId1($id_rutina)
	{
		$query = '
			SELECT
			Mus.id,
			Mus.nb_musculo,
			Tip.nb_TipoRutina
			FROM sg_musculos Mus
			
			INNER JOIN sg_tiposrutina Tip on Mus.id_TipoRutina = Tip.id
			
			WHERE Tip.id = "'.$id_rutina.'" and Mus.sn_activo=1
		';
	    $con    = Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMusculosPorTipoRutinaId
	
	function _ConsultarMaquinasGeneral()
	{
		$query='
			SELECT 
			ma.id,
			ma.desc_maquina,
			ma.nb_maquina,
			ma.num_maquina,
			ca.nb_CategoriaMaquina as "Categoria"
			
			FROM sg_maquinas ma
			INNER JOIN sg_categoriamaquina ca 
			on ca.id=ma.id_categoriamaquina
			WHERE ma.sn_activo=1 
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMaquinasGeneral
	
	function _ConsultarMaquinas1($tipo_musculo)
	{
		$query='
			SELECT 
			ma.id,
			ma.desc_maquina,
			ma.nb_maquina,
			ma.num_maquina,
			ca.nb_CategoriaMaquina as "Tipo_Musculo",
			ca.nb_CategoriaMaquina as "Categoria"
			
			FROM sg_maquinas ma
			INNER JOIN sg_categoriamaquina ca 
			on ca.id=ma.id_CategoriaMaquina
			WHERE ma.sn_activo=1 and ma.id_CategoriaMaquina="'.$tipo_musculo.'"
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMaquinas
	
	function _ConsultarMaquinaPorId($id)
	{
		$query='select * from sg_maquinas where id="'.$id.'"';		
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMaquinaPorId
	
	function _ConsultarMaquinaPorMusculo($id_musculo)
	{
		$query='
			SELECT
			Ma.id,
			Ma.nb_maquina,
			Ma.desc_maquina,
			Mus.nb_musculo
			FROM 
			sg_maquinas Ma
			INNER JOIN
			sg_musculos Mus
			ON Mus.id=Ma.Tipo_Musculo
			WHERE Mus.id="'.$id_musculo.'" 
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMaquinaPorMusculo
	
	function _ConsultarMaquinasPorEjercicios($id_maquina)
	{
		$query='
			select 
			Ejercicios.id as "id_ejercicio",
			Ejercicios.nb_ejercicio,
			Maquinas.id as "id_maquina",
			Maquinas.nb_maquina,
			Maquinas.num_maquina,
			Musculos.id as "id_musculo",
			Musculos.nb_musculo
			
			from
			
			sg_ejercicios Ejercicios
			
			LEFT JOIN sg_maquinas Maquinas on Maquinas.id = Ejercicios.id_maquina
			LEFT JOIN sg_musculos Musculos on Musculos.id = Ejercicios.id_musculo
			where Maquinas.id = "'.$id_maquina.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarMaquinasPorEjercicios
	
	function _ConsultarCategoriaMaquinasGeneral()
	{
		//Aquí se consulta la cateogría de las m´quinas por ID
		$query='
			SELECT * FROM sg_categoriamaquina where sn_activo=1;
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarCategoriaPorId
	
	function _ConsultarMaquinaPorCategoria($id_Categoria)
	{
		$query = 'select * from sg_maquinas where id_CategoriaMaquina = "'.$id_Categoria.'"';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMaquinaPorCategoria
	
	function _ConsultarCategoriaPorId($id)
	{
		//Aquí se consulta la cateogría de las m´quinas por ID
		$query='
			SELECT * FROM sg_CategoriaMaquina where id="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarCategoriaPorId
	
	
	function _ConsultarMusculos1()
	{
		$query='
		select  
		Mus.id,
		Mus.nb_musculo,
		Mus.desc_musculo,
		us.nb_nombre,
		us.nb_apellidos,
		Rut.nb_TipoRutina
		from 
		sg_musculos Mus
		INNER JOIN sg_usuarios us
		on Mus.id_usuario_creacion=us.id_usuario
		INNER JOIN sg_tiposrutina Rut
		ON Rut.id=Mus.id_TipoRutina
		WHERE Mus.sn_activo=1
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMusculos
	
	function _ConsultarMusculosPorRutina1($Tipo_Rutina)
	{
		$query='
		select  
		Mus.id,
		Mus.nb_musculo,
		Mus.desc_musculo,
		us.nb_nombre,
		us.nb_apellidos,
		Rut.nb_TipoRutina
		from 
		sg_musculos Mus
		INNER JOIN sg_usuarios us
		on Mus.id_usuario_creacion=us.id_usuario
		INNER JOIN sg_tiposrutina Rut
		ON Rut.id=Mus.id_TipoRutina
		WHERE Mus.sn_activo=1
		AND Rut.id="'.$Tipo_Rutina.'" 
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMusculos
	
	function _ConsultarMusculosPorId1($id)
	{
		$query='
		select  
		Mus.id,
		Mus.nb_musculo,
		Mus.desc_musculo,
		us.nb_nombre,
		us.nb_apellidos,
		Rut.nb_TipoRutina,
		Rut.id AS "id_TipoRutina"
		from 
		sg_musculos Mus
		INNER JOIN sg_usuarios us
		on Mus.id_usuario_creacion=us.id_usuario
		INNER JOIN sg_tiposrutina Rut
		ON Rut.id=Mus.id_TipoRutina
		WHERE Mus.sn_activo=1 AND Mus.id="'.$id.'"
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMusculosPorId
	
	function _ConsultarMusculosAsignadosEjerciciosPorId($id)
	{
		$query = '
			SELECT
			Musc.id,
			Musc.nb_musculo,
			Ejer.nb_ejercicio,
			Tip.nb_TipoRutina
			
			FROM sg_musculos Musc
			INNER JOIN sg_ejercicios Ejer on Ejer.id_musculo = Musc.id
			INNER JOIN sg_tiposrutina Tip on Ejer.id_TipoRutina = Tip.id
			
			where musc.id = "'.$id.'" and Ejer.sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarMusculosAsignadosEjerciciosPorId
	
	//Métodos para rutinas
	function _ConsultarCategoriasDeRutinas()
	{
		$query='
		SELECT
		Cat.id,
		Cat.nb_CategoriaRutina
		
		FROM sg_categoriasrutina Cat
		WHERE Cat.sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarCategoriasDeRutinas	
		
	
	
	function _ConsultarTipoRutinaPorId1($id)
	{
		$query='
			SELECT
			TR.id,
			TR.nb_TipoRutina,
			TR.desc_TipoRutina,
			US.nb_nombre,
			US.nb_apellidos
			from sg_tiposrutina TR
			INNER JOIN sg_usuarios US
			ON US.id_usuario=TR.id_UsuarioRegistro
			WHERE TR.sn_activo=1 AND id="'.$id.'"
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarTipoRutinaPorId
	
	function _BuscarRutinasPorMusculo($id_musculo)
	{
		$query='
			SELECT
			Rut.id,
			Rut.nb_TipoRutina
			FROM sg_musculos as mus
			INNER JOIN sg_tiposrutina Rut
			ON Rut.id=mus.id_TipoRutina
			where mus.id="'.$id_musculo.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//BuscarRutinasPorMusculo
	
	
	
	
	
	function _ConsultarEjercicios1()
	{
		$query='
			select 
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Tip.nb_TipoRutina
			FROM sg_ejercicios Ej
			INNER JOIN sg_usuarios Us
			ON Us.id_usuario=Ej.id_UsuarioCreacion
			INNER JOIN sg_musculos Mus
			ON Mus.id= Ej.id_musculo
			INNER JOIN sg_tiposrutina Tip
			ON Tip.id=Ej.id_TipoRutina
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarEjercicios
	
	
	
	function _ConsultarEjerciciosTipoRutinaGeneral()
	{
		$query = '
			select 
			Ej.id,
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Ej.id_TipoRutina,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Tip.nb_TipoRutina,
			MA.nb_maquina
			FROM sg_ejercicios Ej
			INNER JOIN sg_usuarios Us
			ON Us.id_usuario=Ej.id_UsuarioCreacion
			INNER JOIN sg_musculos Mus
			ON Mus.id= Ej.id_musculo
			INNER JOIN sg_tiposrutina Tip
			ON Tip.id=Ej.id_TipoRutina
			LEFT JOIN sg_maquinas MA
			ON Ej.id_maquina=MA.id
			WHERE Ej.sn_activo=1 order by nb_TipoRutina
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarEjerciciosTipoRutinaGeneral
	
	function _ConsultarEjerciciosPorTipoRutina1($Tipo_Rutina)
	{
		$query='
			select 
			Ej.id,
			Ej.desc_ejercicio,
			Ej.nb_ejercicio,
			Ej.id_TipoRutina,
			Us.nb_nombre,
			Us.nb_apellidos,
			Mus.nb_musculo,
			Tip.nb_TipoRutina,
			MA.nb_maquina
			FROM sg_ejercicios Ej
			INNER JOIN sg_usuarios Us
			ON Us.id_usuario=Ej.id_UsuarioCreacion
			INNER JOIN sg_musculos Mus
			ON Mus.id= Ej.id_musculo
			INNER JOIN sg_tiposrutina Tip
			ON Tip.id=Ej.id_TipoRutina
			LEFT JOIN sg_maquinas MA
			ON Ej.id_maquina=MA.id
			WHERE Tip.id="'.$Tipo_Rutina.'"
			AND Ej.sn_activo=1
		';
		$con	= Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarEjerciciosPorId
	
	function _ConsultarTiposRutinaPorMusculo($id_musculo)
	{
		$query = '
			select DISTINCT
			
			Mus.nb_musculo,
			Tip.nb_TipoRutina,
			Tip.id
			
			FROM sg_musculos Mus
			
			INNER JOIN sg_tiposrutina Tip ON Tip.id= Mus.id_TipoRutina
			WHERE Mus.id = "'.$id_musculo.'"
			AND Tip.sn_activo=1
		';
		$con	= Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarTiposRutinaPorMusculo
	
	function _ConsultarExistenciaEjerciciosRutinas($id_ejercicio)
	{
		$query  = '
		select DISTINCT
		Ejer.id_Ejercicio,
		Eje.nb_ejercicio,
		Rut.nb_rutina,
		Cat.nb_CategoriaRutina as categoria
		
		from sg_ejerciciosrutina Ejer
		INNER JOIN sg_rutinas Rut on Rut.id_rutina = Ejer.id_rutina
		INNER JOIN sg_ejercicios Eje ON Eje.id = Ejer.id_Ejercicio
		INNER JOIN sg_categoriasrutina  Cat ON Rut.id_CategoriaRutina = Cat.id
		where id_Ejercicio = "'.$id_ejercicio.'" and Rut.sn_activo = 1
		';
		$con	= Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarExistenciaEjerciciosRutinas
	
	function _ConsultarExistenciaEjerciciosRutinasClientes($id_ejercicio)
	{
		$query  = '
		select DISTINCT
		Ejer.id_Ejercicio,
		Eje.nb_ejercicio,
		Rut.nb_rutina,
		Cat.nb_CategoriaRutina as categoria,
		Clie.nb_cliente,
		Clie.nb_apellidos	
		
		from sg_ejerciciosrutinacliente Ejer
		INNER JOIN sg_rutinasclientes Rut on Rut.id_rutinaCliente = Ejer.id_rutina
		INNER JOIN sg_ejercicios Eje ON Eje.id = Ejer.id_Ejercicio
		INNER JOIN sg_categoriasrutina  Cat ON Rut.id_CategoriaRutina = Cat.id
		INNER JOIN sg_clientes Clie	ON Rut.id_cliente = Clie.id_cliente
		where Ejer.id_Ejercicio = "'.$id_ejercicio.'" and Rut.sn_activo = 1
		';
		$con	= Conectar::_con();
		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;	
	}//_ConsultarExistenciaEjerciciosRutinas
	
	function _ConsultarRutinaPorId($id)
	{
		$query='
			SELECT * FROM sg_rutinas where id_rutina="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinaPorId
	
	function _ConsultarRutinasGeneral()
	{
		$query = '
			SELECT
			Rut.id_rutina,
			Rut.nb_rutina,
			Rut.desc_rutina,
			Rut.fh_Creacion,
			Usu.nb_nombre,
			Usu.nb_apellidos,
			Cat.nb_CategoriaRutina
			FROM sg_rutinas Rut
			INNER JOIN sg_usuarios Usu
			ON Usu.id_usuario=Rut.id_UsuarioCreacion
			INNER JOIN sg_categoriasrutina Cat
			ON Cat.id=Rut.id_CategoriaRutina
			where  Rut.sn_activo=1 order by nb_CategoriaRutina desc
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinasGeneral
	
	function _ConsultarRutinaPorEntrenadorYCategoriaDeRutina($id_usuario, $id_CategoriaRutina,$id_genero)
	{
		// Definiendo como se crea el query,
		// si viene un número diferente de 4 "Significa que son todas las rutinas" se le concatena el id_CategoriaRutina
		// si viene con el 4, se trae todas las rutinas del usuario al que se le solicitaron las rutinas
		
		//Condición de la categoría
		if($id_CategoriaRutina!=4){$Condicion = ' and Rut.id_CategoriaRutina="'.$id_CategoriaRutina.'"';}
		else{$Condicion = "";}
		
		//condición del género
		if($id_genero!="Todos"){$CondicionGenero = 'AND Gen.id="'.$id_genero.'"';}
		else{$CondicionGenero = "";}
		
		$query='
			SELECT
			Rut.id_rutina,
			Rut.nb_rutina,
			Rut.desc_rutina,
			Rut.fh_Creacion,
			Usu.nb_nombre,
			Usu.nb_apellidos,
			Cat.id,
			Cat.nb_CategoriaRutina,
			Gen.nb_TipoRutina,
			cuerpo.id as id_cuerpo,
			cuerpo.nb_cuerpo
			FROM sg_rutinas Rut
			INNER JOIN sg_usuarios Usu
			ON Usu.id_usuario=Rut.id_UsuarioCreacion
			INNER JOIN sg_categoriasrutina Cat
			ON Cat.id=Rut.id_CategoriaRutina
			LEFT JOIN sg_generosrutina Gen
			ON Rut.id_GeneroRutina = Gen.id
			LEFT JOIN sg_tipocuerpo cuerpo
			ON cuerpo.id = Rut.id_tipocuerpo
			where Usu.id_usuario="'.$id_usuario.'" '.$Condicion.' '.$CondicionGenero.'  AND Rut.sn_activo=1 order by Cat.id  ASC
			
		';	
	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinaPorEntrenadorYCategoriaDeRutina
	
	function _ConsultarRutinasPorCategoria($id_categoria,$id_genero)
	{
		//Aquí se dan las rutinas para cualquier entrenador, pero con su categoría dependiendo cual se ha epdido.
		
		//Verificando si se elige algún género de rutina o se traen todos.
		
		$condicion = ($id_genero!="Todos")?'and Gen.id="'.$id_genero.'"':"";
		
		$query = '
			SELECT
			Rut.id_rutina,
			Rut.nb_rutina,
			Rut.desc_rutina,
			Rut.fh_Creacion,
			Usu.nb_nombre,
			Usu.nb_apellidos,
			Cat.nb_CategoriaRutina,
			Gen.nb_TipoRutina 
			FROM sg_rutinas Rut
			INNER JOIN sg_usuarios Usu
			ON Usu.id_usuario=Rut.id_UsuarioCreacion
			INNER JOIN sg_categoriasrutina Cat
			ON Cat.id=Rut.id_CategoriaRutina
			LEFT JOIN sg_generosrutina Gen
			ON Rut.id_GeneroRutina = Gen.id
			where  Rut.id_CategoriaRutina="'.$id_categoria.'" AND Rut.sn_activo=1 '.$condicion.'
		';	
		//echo $query;
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinasPorCategoria
	
	
	
	function _ConsultarRutinaPorNombreYCategoria($nb_rutina, $id_CategoriaRutina)
	{
		$query='
			SELECT *
			FROM
			sg_rutinas Rut
			where
			Rut.nb_rutina ="'.$nb_rutina.'"
			AND Rut.id_CategoriaRutina="'.$id_CategoriaRutina.'" and sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinaPorNombreYCategoria
	
	function _ConsultarRutinaClientePorIdCliente($id_cliente)
	{
		$query='
			SELECT *
			FROM
			sg_rutinasclientes Rut
			where
			Rut.id_cliente ="'.$id_cliente.'" AND Rut.sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinaPorNombreYCategoria
	
	function _ConsultarRutinaClientePorIdEntrenadorYNombreRutina($id_usuario, $nb_rutina)
	{
		
		$query='
			select * from sg_rutinasclientes where id_UsuarioCreacion ="'.$id_usuario.'" and nb_rutina="'.$nb_rutina.'" and sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarRutinaClientePorIdRutinaYNombreRutina
	
	function _ConsultarInfoRutinaVigenteClientePorId($id_cliente)
	{
		$query='
			select * from sg_rutinasclientes where id_cliente="'.$id_cliente.'" and sn_activo=1
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInfoRutinaVigenteClientePorId
	
	function _ConsultarCategoriasRutinas($rutina)
	{
		if($rutina == "Todos")
		{
			$query = 'select * from sg_categoriasrutina where nb_CategoriaRutina = "'.$rutina.'" ';
		}
		else
		{
			$query = 'select * from sg_categoriasrutina where  id = "'.$rutina.'" ';
		}
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarCategoriasRutinas
	
	
	
	function _ConsultarListaEntrenadoresConRutinasSoloNombres()
	{
		$query='
		SELECT DISTINCT
		Usu.id_usuario,
		Usu.nb_nombre,
		Usu.nb_apellidos
		FROM sg_rutinas Rut
		
		INNER JOIN sg_usuarios Usu
		ON Rut.id_UsuarioCreacion=Usu.id_usuario
		WHERE Usu.sn_activo=1
		ORDER BY Usu.id_usuario
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarListaEntrenadoresConRutinasSoloNombres
	
	
	
	function _VerificaExistenciaRutina($nb_rutina, $id_CategoriaRutina)
	{
		$query='
		SELECT 
		* 
		FROM
		sg_rutinas Rut
		where
		Rut.nb_rutina="'.$nb_rutina.'" and Rut.id_CategoriaRutina="'.$id_CategoriaRutina.'" and Rut.sn_activo=1
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//VerificaExistenciaRutina	
	
	
	
	function _ConsultarGenerosRutinaPorEntrenadorYTipoRutina($id_entrenador,$id_categoria)
	{
		//La condición se asignará cuadno venga el identificador de Todos para los usuarios
		$condicion = ($id_entrenador!="Todos")?'Rut.id_UsuarioCreacion="'.$id_entrenador.'" AND':"";
		$query = '
			SELECT DISTINCT
			Gen.nb_TipoRutina,
			Gen.id
			
			FROM sg_rutinas Rut
			
			INNER JOIN sg_usuarios Usu
			ON Rut.id_UsuarioCreacion=Usu.id_usuario
			LEFT JOIN sg_generosrutina Gen
			on Rut.id_GeneroRutina = Gen.id
			LEFT JOIN sg_categoriasrutina Cat
			ON Rut.id_CategoriaRutina = Cat.id
			WHERE '.$condicion.' Rut.id_CategoriaRutina="'.$id_categoria.'"
			
			ORDER BY Usu.id_usuario
		';	
		//echo $query;
		$con	= Conectar::_con();
 		$result = $con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarGenerosRutinaPorEntrenador
	
	// Final de funciones de rutinas //
	
	function _ConsultarDiaPorId($id_dia)
	{
		$query='
		SELECT *  FROM sg_dias where id="'.$id_dia.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarDiaPorId
	
	
	
	function _ConsultarDiaPorNombre($nb_dia)
	{
		$query='
		SELECT *  FROM sg_dias where nb_dia="'.$nb_dia.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarDiaPorNombre
	
	function _ConsultarDiaPorDesc($desc_dia)
	{
		$query='
		SELECT *  FROM sg_dias where desc_dia="'.$desc_dia.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarDiaPorNombre
	
	function _ConsultarInformacionRutinaPorId($id_rutina)
	{
		$query='
			select * from sg_rutinas where id_rutina= "'.$id_rutina.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInformacionRutinaPorId
	
	
	
	function _ConsultarInformacionRutinaPreFinalClientePorId($id)
	{
		$query='
		SELECT 
		/* Datos de la tabla sg_rutinas*/
		Rut.id as id_rutinacliente,
		Rut.id_usuariocreacion,
		Rut.fh_creacion,
		Rut.nb_rutina,
		Rut.desc_rutina,

		/* Datos de Categoría Rutina*/
		CatRu.nb_categoriarutina,

		/* Datos de la tabla sg_ejercicios*/
		Ejer.nb_ejercicio,

		/* Datos de ejercicio sg_ejerciciosrutinacliente*/
		Eje.num_circuitos,
		Eje.num_repeticiones,
		Eje.id as "id_ejercicio",
		Eje.id_dia,
		Eje.id_posicionejercicio,
		Eje.ejercicio_relacion,
		/* Datos de la tabla sg_tiposrutina*/
		TipRu.nb_tiporutina,

		/* DAtos de la tabla días*/
		dias.nb_dia,
		dias.id as id_dia,
		/* Datos de sg_musculos*/
		Musc.nb_musculo,

		/* Datos de la tabla sg_usuarios*/
		Usuarios.nb_nombre,
		Usuarios.nb_apellidos,

		/* Datos de la tabla sg_maquinas */
		Maq.id,
		Maq.nb_maquina,
		Maq.num_maquina

		FROM
		sgrutinasclientes Rut

		/* JOINS*/
		LEFT JOIN 
		sgejerciciosrutinacliente Eje
		ON Eje.id_Rutina

		INNER JOIN sgcategoriasrutina CatRu
		ON CatRu.id=Rut.id_categoriarutina

		INNER JOIN sgejercicios Ejer
		ON Eje.id_ejercicio=Ejer.id

		INNER JOIN sgtiposrutina TipRu
		ON TipRu.id=Ejer.id_tiporutina

		INNER JOIN sgdias dias
		ON Eje.id_dia=dias.id

		INNER JOIN sgmusculos Musc
		ON Musc.id=Ejer.id_musculo

		INNER JOIN sgusuarios Usuarios ON
		Rut.id_usuariocreacion=Usuarios.id

		LEFT JOIN sgmaquinas Maq ON
		Ejer.id_maquina = Maq.id

		where Rut.id=? AND Eje.id_rutina=? AND Eje.sn_activo=1 
		ORDER BY dias.id,Eje.id_posicionejercicio asc, id_ejercicio asc
			';
		R::freeze(1);
		R::begin();
			    try{
			       $ejercicios = R::getAll($query,[$id,$id]);
			        R::commit();
			    }
			    catch(Exception $e) {
			       $ejercicios =  R::rollback();
			       $ejercicios = "Error";
			    }
			R::close();
			return $ejercicios;
	}//_ConsultarInformacionRutinaPreFinalPorId
	
	//Método para el módulo de Rutinas
	
	
	//Método para el módulo de clientes
	function _ConsultarInformacionPorRutinaYDia($id_rutina, $id_dia)
	{
		$query = '
			SELECT 
			/* Datos de la tabla sg_rutinas*/
			Rut.id_rutinaCliente,
			Rut.id_UsuarioCreacion,
			Rut.fh_Creacion,
			Rut.nb_rutina,
			Rut.desc_rutina,
			
			/* Datos de Categoría Rutina*/
			CatRu.nb_CategoriaRutina,
			
			/* Datos de la tabla sg_ejercicios*/
			Ejer.nb_ejercicio,
			
			/* Datos de ejercicio sg_ejerciciosrutina*/
			Eje.num_Circuitos,
			Eje.num_Repeticiones,
			Eje.id_EjercicioRutinaCliente as "id_ejercicio",
			Eje.id_dia,
			Eje.id_PosicionEjercicio,
			/* Datos de la tabla sg_tiposrutina*/
			TipRu.nb_TipoRutina,
			
			/* DAtos de la tabla días*/
			dias.nb_dia,
			
			/* Datos de sg_musculos*/
			Musc.nb_musculo,
			
			/* Datos de la tabla sg_usuarios*/
			Usuarios.nb_nombre,
			Usuarios.nb_apellidos
			
			FROM
			sg_rutinasclientes Rut
			
			/* JOINS*/
			INNER JOIN 
			sg_ejerciciosrutinacliente Eje
			ON Eje.id_rutina
			
			INNER JOIN sg_categoriasrutina CatRu
			ON CatRu.id=Rut.id_CategoriaRutina
			
			INNER JOIN sg_ejercicios Ejer
			ON Eje.id_Ejercicio=Ejer.id
			
			INNER JOIN sg_tiposrutina TipRu
			ON TipRu.id=Ejer.id_TipoRutina
			
			INNER JOIN sg_dias dias
			ON Eje.id_dia=dias.id
			
			INNER JOIN sg_musculos Musc
			ON Musc.id=Ejer.id_musculo
			
			INNER JOIN sg_usuarios Usuarios ON
			Rut.id_UsuarioCreacion=Usuarios.id_usuario
			where Rut.id_rutinaCliente="'.$id_rutina.'" AND Eje.id_Rutina="'.$id_rutina.'" AND Eje.sn_activo=1 and Eje.id_dia="'.$id_dia.'"
				ORDER BY dias.id,id_posicionejercicio asc, id_ejercicio asc 
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInformacionPorRutinaYDia
	
	
	
	function _ConsultarNumeroDeRepeticionesEjercicioPorId($id)
	{
		$query='
			select * FROM sg_ejerciciosrutina  rut where rut.id="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarNumeroDeRepeticionesEjercicioPorId
	
	function _ConsultarNumeroDeRepeticionesEjercicioClientesPorId($id)
	{
		$query='
			select * FROM sg_ejerciciosrutinacliente  rut where rut.id="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarNumeroDeRepeticionesEjercicioPorId
	
		function _ConsultarNumeroDeCircuitosEjercicioPorId($id)
	{
		$query='
			select * FROM sg_ejerciciosrutina  rut where rut.id="'.$id.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarNumeroDeRepeticionesEjercicioPorId
	
	function _ConsultarEjerciciosRutinasPorDiaYPorIdRutina($id_dia, $id_rutina)
	{
		$query='
			select DISTINCT
			/*Datos de sg_ejerciciosrutina */
			Cat.id,
			Cat.nb_CategoriaRutina,
			
			/* datos from sg_dias*/
			Dias.nb_dia,
			dias.id as "id_dia",
			
			/* Datos de sg_tiposrutina*/
			Tip.nb_TipoRutina,
			Tip.id as "id_tipoRutina"
			from sg_rutinas Rut
			
			/* JOINS*/
			INNER JOIN sg_ejerciciosrutina Eje
			ON Eje.id_rutina=Rut.id_rutina
			
			INNER JOIN sg_categoriasrutina Cat
			ON Cat.id=Eje.id_CategoriaRutina
			
			INNER JOIN sg_dias Dias
			ON Dias.id=Eje.id_dia
			
			INNER JOIN sg_tiposrutina Tip
			ON Eje.id_TipoRutinaEjercicio=Tip.id
			
			WHERE Rut.id_rutina="'.$id_rutina.'" and dias.id="'.$id_dia.'"
		';	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarEjerciciosRutinasPorDiaYPorIdRutina
	
	
	
	function _ConsultarInfoFormularioPorIdCliente($id_cliente)
	{
		$query='
		SELECT
		Form.Dias_Semana,
		Form.Minutos_Dia,
		Form.Resultado_Ejercicio,
		clie.nb_cliente,
		clie.nb_apellidos
		
		FROM
		sg_formulario Form
		
		INNER JOIN sg_clientes clie
		on Form.id_cliente=clie.id_cliente

		WHERE clie.id_cliente="'.$id_cliente.'"
		';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarInfoFormularioPorIdCliente
	
	
	
	// Querys de reportes
	
	function _ReporteEntrenadoresRutinasYBiotest()
	{
		$query = "
			select DISTINCT
			IFNULL(sub_p.rutinas, 0) Rutina_Asignada,
		  IFNULL(Sub_rut.rutinascreadas, 0) Rutina_Creada,
		  IFNULL(Sub_Biotest.Biotest, 0) biotest_hecho,
			Us.nb_nombre,
			Us.nb_apellidos,
			Us.id_usuario
			
			from sg_usuarios Us 
		
			
left JOIN (
	SELECT COUNT(*) rutinas, 	id_UsuarioCreacion from sg_rutinasclientes GROUP BY id_UsuarioCreacion
) sub_p ON (sub_p.id_UsuarioCreacion = Us.id_usuario)

left JOIN (
	SELECT COUNT(*) rutinascreadas, id_UsuarioCreacion  from sg_rutinas GROUP BY id_UsuarioCreacion
) Sub_rut ON (Sub_rut.id_UsuarioCreacion = Us.id_usuario)

left JOIN (
	SELECT COUNT(*) Biotest, id_UsuarioCreacion  from sg_biotestbitacora GROUP BY id_UsuarioCreacion
) Sub_Biotest ON (Sub_Biotest.id_UsuarioCreacion = Us.id_usuario)
			
		where Us.sn_activo=1 GROUP BY Us.id_usuario
		
		";
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ReporteEntrenadoresRutinasYBiotest
	
	function _ReporteCantidadBiotestClientes()
	{
		$query = "
			select DISTINCT
			IFNULL(sub_bio.Biotest, 0) 'Cantidad_Biotest',
			IFNULL(sub_fh.fh_creacion, 0) 'fh_biotest',
			Clie.nb_cliente as 'Cliente_nombre',
			Clie.nb_apellidos as 'Cliente_Apellidos',
			Clie.id_cliente,
			Clie.num_celular,
			Clie.de_email,
			Usu.id_usuario,
			Usu.nb_nombre as 'Entrenador_nombre',
			Usu.nb_apellidos as 'Entrenador_Apellidos'
			
			
			/*from sg_BiotestBitacora Bit */
			from sgclientes Clie
			
			left JOIN (
			SELECT COUNT(*) Biotest, id_cliente from sg_biotestbitacora GROUP BY id_cliente
			) sub_bio ON (sub_bio.id_cliente = Clie.id_cliente)
			
			left JOIN (
			SELECT  	max(fh_creacion) as 'fh_creacion', id_cliente from sg_biotestbitacora GROUP BY id_cliente
			) sub_fh ON (sub_fh.id_cliente = Clie.id_cliente)
			
			
			
			LEFT JOIN sg_usuarios Usu
			ON Clie.id_usuario_registro = Usu.id_usuario
			
			
			WHERE Clie.sn_activo = 1
			
			GROUP BY Clie.id_cliente 
			ORDER BY 'Cantidad_Biotest'



		";	
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ReporteCantidadBiotestClientes
	
	

	function _ConsultarCuerpoPorTexto($texto)
	{
		$query='SELECT * from sg_tipocuerpo where nb_cuerpo = "'.$texto.'"';
		$con=Conectar::_con();
		$result=$con->query($query) or die("Error en: $query ".mysqli_error($query));
		return $result;
	}//_ConsultarCuerpoPorTexto

}//Consultar

?>