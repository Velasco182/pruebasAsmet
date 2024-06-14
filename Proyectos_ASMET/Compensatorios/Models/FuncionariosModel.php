<?php 

	class FuncionariosModel extends Oracle{
		private $intIdFuncionario;
		private $strIdentificacion;
		private $strNombre;
		private $strApellido;
		private $strUsuario;
		private $strEmail;
		private $strPassword;
		private $strToken;
		private $intTipoId;
		private $intStatus;

		public function __construct(){
			parent::__construct();
		}

		public function insertFuncionario(string $identificacion, string $nombre, string $apellido,
			 string $usuario, string $email, string $password, int $tipoid, int $status){

			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->intUsuario = $usuario;
			$this->strEmail = $email;
			$this->strPassword = $password;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;
			$return = 0;

			$sql = "SELECT * FROM BIG_FUNCIONARIOS 
				WHERE FUN_CORREO = '{$this->strEmail}' or FUN_USUARIO = '{$this->intUsuario}'";
			$request = $this->select_all($sql);

			if(empty($request)){
				$query_insert  = "
				INSERT INTO BIG_FUNCIONARIOS
				(
					FUN_IDENTIFICACION,
					FUN_NOMBRES,
					FUN_APELLIDOS,
					FUN_USUARIO,
					FUN_CORREO,
					FUN_PASSWORD,
					ID_ROL,
					FUN_ESTADO
				) 
				VALUES
				(
					:FUN_IDENTIFICACION,:FUN_NOMBRES,:FUN_APELLIDOS,:FUN_USUARIO,:FUN_CORREO,
					:FUN_PASSWORD,:ID_ROL,:FUN_ESTADO
				)";
	        	$arrData = array(
					'FUN_IDENTIFICACION'=>$this->strIdentificacion,
        			'FUN_NOMBRES'=>$this->strNombre,
        			'FUN_APELLIDOS'=>$this->strApellido,
        			'FUN_USUARIO'=>$this->intUsuario,
        			'FUN_CORREO'=>$this->strEmail,
        			'FUN_PASSWORD'=>$this->strPassword,
        			'ID_ROL'=>$this->intTipoId,
        			'FUN_ESTADO'=>$this->intStatus
				);
				
	        	$request_insert = $this->insert($query_insert,$arrData);
	        	$return = $request_insert;
			}else{
				$return = "exist";
			}
	        return $return;
		}

		public function selectFuncionarios(){
		
			$sql = "SELECT 
				p.ID_FUNCIONARIO,p.FUN_IDENTIFICACION,p.FUN_NOMBRES,p.FUN_APELLIDOS,
				p.FUN_USUARIO,p.FUN_CORREO,p.FUN_ESTADO,r.ID_ROL,r.ROL_NOMBRE,P.FUN_ADMIN
				FROM BIG_FUNCIONARIOS p 
				INNER JOIN BIG_ROLES r ON p.ID_ROL = r.ID_ROL
				WHERE p.FUN_ESTADO IS NOT NULL";

			$request = $this->select_all($sql);
			return $request;
		}

		public function selectFuncionario(int $idfuncionario){
			$this->intIdFuncionario = $idfuncionario;
			$sql = "
			SELECT 
				p.ID_FUNCIONARIO,
				p.FUN_IDENTIFICACION,
				p.FUN_NOMBRES,
				p.FUN_APELLIDOS,
				p.FUN_USUARIO,
				p.FUN_CORREO,
				p.FUN_ESTADO,
				r.ID_ROL,
				r.ROL_NOMBRE,
				P.FUN_ADMIN 
			FROM BIG_FUNCIONARIOS p 
			INNER JOIN BIG_ROLES r ON p.ID_ROL = r.ID_ROL
			WHERE p.ID_FUNCIONARIO = $this->intIdFuncionario";
			$request = $this->select($sql);
			// print_r($request);
			return $request;
		}

		public function updateFuncionario(int $idFuncionario, string $identificacion, string $nombre, string $apellido, 
			string $usuario, string $email,int $tipoid, int $status){

			$this->intIdFuncionario = $idFuncionario;
			$this->strIdentificacion = $identificacion;
			$this->strNombre = $nombre;
			$this->strApellido = $apellido;
			$this->strUsuario = $usuario;
			$this->strEmail = $email;
			$this->intTipoId = $tipoid;
			$this->intStatus = $status;

			$sql = "SELECT * FROM BIG_FUNCIONARIOS
				WHERE 
				(FUN_CORREO = '{$this->strEmail}' OR FUN_USUARIO = '{$this->strUsuario}') 
				and ID_FUNCIONARIO!='{$this->intIdFuncionario}'
			";
			//dep($sql);
			$request = $this->select_all($sql);

			if(empty($request)){
				$sql = "
				UPDATE BIG_FUNCIONARIOS SET FUN_IDENTIFICACION=:FUN_IDENTIFICACION,
				FUN_NOMBRES=:FUN_NOMBRES,
				FUN_APELLIDOS=:FUN_APELLIDOS,
				FUN_USUARIO=:FUN_USUARIO,
				FUN_CORREO=:FUN_CORREO,
				ID_ROL=:ID_ROL,
				FUN_ESTADO=:FUN_ESTADO 
				WHERE ID_FUNCIONARIO = $this->intIdFuncionario 
				";
				$arrData = array(
					'FUN_IDENTIFICACION'=>$this->strIdentificacion,
					'FUN_NOMBRES'=>$this->strNombre,
					'FUN_APELLIDOS'=>$this->strApellido,
					'FUN_USUARIO'=>$this->strUsuario,
					'FUN_CORREO'=>$this->strEmail,
					'ID_ROL'=>$this->intTipoId,
					'FUN_ESTADO'=>$this->intStatus
				);
				
				
				$request = $this->update($sql,$arrData);
			}else{
				$request = "exist";
			}
			return $request;
		}

		public function deleteFuncionario(int $idFuncionario){
			$this->intIdFuncionario = $idFuncionario;
			$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_ESTADO = :FUN_ESTADO WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
			$arrData = array(
				'FUN_ESTADO'=>$estado
			);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function estadoFuncionario(int $idFuncionario, string $estado){
			$this->intIdFuncionario = $idFuncionario;
			$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_ESTADO = ? WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
			$arrData = array($estado);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function resetPassFuncionario(int $idFuncionario,string $fun_password){
			$this->intIdFuncionario = $idFuncionario;
			$sql = "UPDATE BIG_FUNCIONARIOS SET FUN_PASSWORD = ? WHERE ID_FUNCIONARIO = $this->intIdFuncionario ";
			$arrData = array($fun_password);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function selectRoles(){
			$sql = "SELECT * FROM BIG_ROLES  
				where  ROL_ESTADO='1'
				ORDER BY ROL_NOMBRE ASC";
			$request = $this->select_all($sql);
			return $request;
		}
	}
 ?>