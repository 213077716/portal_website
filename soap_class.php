<?php
	class SOAP{
		private $sWSDL;
		public $oClient;
		//public $sUsername;
		//public $sPassword;
		public $iSessionID;
		
		function __construct(){
			$this->sWSDL = "https://www.placementpartner.co.za/ws/clients/?wsdl";
			$this->oClient = new SoapClient($this->sWSDL);	
		}
		
		function login_to_portal($sUsername, $sPassword){
			
			try{
				$this->iSessionID = $this->oClient->login($sUsername, $sPassword);
				
				if($this->iSessionID){	
					session_start();
					$_SESSION['id'] = $this->iSessionID;
					header('Location:welcome.php');	
				}				
			}
			catch(Exception $e){
				echo $e->getMessage();
			}	
		}
		
		/*function get_all_adverts(){
			try{
				$getAdvertRegion = $this->oClient->getAdvertRegions($_SESSION['id']);
			}
			catch(Exception $e){
				echo $e->getMessage();	
			}
			
		}*/
		
		function logout_to_portal(){
			
			try{
				$this->oClient->logout($_SESSION['id']);	
				$_SESSION = array();
				session_destroy();
			}
			catch(Exception $e){
				echo $e->getMessage();
			}	
		}
			
	}

?>