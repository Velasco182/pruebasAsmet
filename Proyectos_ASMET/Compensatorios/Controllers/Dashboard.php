<?php
	class Dashboard extends Controllers{
		public function __construct(){
			parent::__construct();
			session_start();
			session_regenerate_id();
			if(empty($_SESSION['login'])){
				header('Location: '.base_url().'/login');
				die();
			}else{
				getPermisos(COD_MOD_DAS);
			}
		}

		public function dashboard(){
			$data['page_tag'] = "Dashboard";
			$data['page_title'] = "Dashboard";
			$data['page_dashboard'] = "TUCAS - Dashboard";
			$data['page_name'] = "Dashboard";
			$data['page_icono'] = "fa-dashboard";
			$data['page_acceso'] = "dashboard";
			$data['page_functions_js'] = "functions_dashboard.js";
			$this->views->getView($this,"dashboard",$data);
		}

		public function getModulos(){
			$arrData = $this->model->listarModulos();
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);	
		}
	}
 ?>