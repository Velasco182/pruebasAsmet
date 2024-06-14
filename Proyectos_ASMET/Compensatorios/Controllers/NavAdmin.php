<?php
	class NavAdmin extends Controllers{
		public function __construct(){
			parent::__construct();
		}
		
		public function getMenu(){
			$arrData = $this->model->listarModulos();
			echo json_encode($arrData,JSON_UNESCAPED_UNICODE);	
		}
	}
 ?>