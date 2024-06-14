<?php 

	class Home extends Controllers{
		public function __construct(){
			session_start();
			session_regenerate_id(true);
			if(isset($_SESSION['login'])){
				header('Location: '.base_url().'/dashboard');
			}
			parent::__construct();
		}

		public function home(){
			$data['page_tag'] = "Inicio";
			$data['page_title'] = "Inicio";
			$data['page_name'] = "Inicio";
			$data['page_icono'] = "fa-book";
			$data['page_acceso'] = "home";
			$data['page_functions_js'] = "functions_home.js";
			$this->views->getView($this,"home",$data);
		}
	}
 ?>