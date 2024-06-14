<?php 

	class LoginModel extends Oracle
	{
		private $intIdUsuario;
		private $strUsuario;
		private $strPassword;
		private $strToken;

		public function __construct()
		{
			parent::__construct();
		}

		public function loginUser(string $usuario, string $password)
		{
			$this->strUsuario = $usuario;
			$this->strPassword = $password;
			$sql = "
			SELECT
				FUN.ID_FUNCIONARIO,
				FUN.FUN_ESTADO 
				FROM BIG_FUNCIONARIOS FUN WHERE 
				FUN.FUN_USUARIO = '$this->strUsuario' and 
				FUN.FUN_PASSWORD = '$this->strPassword' and 
				FUN.FUN_ESTADO = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function sessionLogin(int $iduser){
			$this->intIdUsuario = $iduser;
			//BUSCAR ROLE 
			$sql = "
				SELECT  
					FUN.ID_FUNCIONARIO,
					FUN.FUN_IDENTIFICACION,
					FUN.FUN_NOMBRES,
					FUN.FUN_APELLIDOS,
					FUN.FUN_CORREO,
					FUN.FUN_USUARIO,
					FUN.FUN_PASSWORD,
					FUN.FUN_ESTADO,
					FUN.ID_ROL,
					ROL.ROL_NOMBRE,
					ROL.ROL_CODIGO
			FROM BIG_FUNCIONARIOS FUN
			INNER JOIN BIG_ROLES ROL ON FUN.ID_ROL = ROL.ID_ROL
			WHERE FUN.ID_FUNCIONARIO = $this->intIdUsuario";
			$request = $this->select($sql);
			$_SESSION['userData'] = $request;
			return $request;
		}

		public function getUserEmail(string $strEmail){
			$this->strUsuario = $strEmail;
			$sql = "
					SELECT 
						idpersona,
						nombres,
						apellidos,
						status 
						FROM persona WHERE 
						email_user = '$this->strUsuario' and  
						status = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function setTokenUser(int $idpersona, string $token){
			$this->intIdUsuario = $idpersona;
			$this->strToken = $token;
			$sql = "UPDATE persona SET token = ? WHERE idpersona = $this->intIdUsuario ";
			$arrData = array($this->strToken);
			$request = $this->update($sql,$arrData);
			return $request;
		}

		public function getUsuario(string $email, string $token){
			$this->strUsuario = $email;
			$this->strToken = $token;
			$sql = "SELECT idpersona FROM persona WHERE 
					email_user = '$this->strUsuario' and 
					token = '$this->strToken' and 					
					status = 1 ";
			$request = $this->select($sql);
			return $request;
		}

		public function insertPassword(int $idPersona, string $password){
			$this->intIdUsuario = $idPersona;
			$this->strPassword = $password;
			$sql = "UPDATE persona SET password = ?, token = ? WHERE idpersona = $this->intIdUsuario ";
			$arrData = array($this->strPassword,"");
			$request = $this->update($sql,$arrData);
			return $request;
		}
	}
 ?>