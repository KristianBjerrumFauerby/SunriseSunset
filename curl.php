<?php

class curl {
	public $curl;
	private $baseUrl;
	private $uri;
	
	function __construct ($baseUrl) {
		$this->baseUrl = $baseUrl;
		$this->curl = curl_init();
	}
	
	/**
	 *@desc Set method curl option
	 *@param string $method - POST,GET,PUT 
	 *@return void
	*/
	public function setMethod($method){
		switch($method){
			case 'POST': 
				curl_setopt($this->curl, CURLOPT_POST, 1);
				break;
			case 'GET':
				curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
				break;
			case 'PUT':
				curl_setopt($this->curl, CURLOPT_PUT, 1);
				break;
			default:
				curl_setopt($this->curl, CURLOPT_HTTPGET, 1);
				break;
		}
	}
	
	/**
	 *@desc Set base url to global
	 *@param string $baseUrl - https://example.org
	 *@return void
	*/
	public function setBaseUrl($baseUrl){
		$this->baseUrl = $baseUrl;
	}
	
	/**
	 *@desc Set uri to global
	 *@param string $baseUrl - /resource?
	 *@return void
	*/
	public function setUri($uri){
		$this->uri = $uri;
	}
	
	/**
	 *@desc bind data to curl 
	 *@param array/object $data - need to be array or object else ignored
	 *@return void
	*/
	public function setData($data){
		if(is_array($data) || is_object($data)){
			curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));		
		}
	}
	
	/**
	 *@desc Set url curl option and execute curl  
	 *@param boolean $returnData - defines if bool is returned or data from curl
	 *@return bool/json_string
	*/
	public function sendRequest ($returnData){
		$this->setReturn($returnData);
		curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . $this->uri);
		$result = curl_exec($this->curl);
		
		return $result;
	}
	
	/**
	 *@desc Close the instance of curl 
	 *@return void
	*/
	public function closeCurl(){
		curl_close($this->curl);
	}
	
	/**
	 *@desc Set return option to curl option
	 *@param boolean $returnData - defines if bool is returned or data from curl	 
	 *@return void
	*/
	private function setReturn($returnData){
		if($returnData){
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
		}else{
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 0);
		}		
	}
	
}
?>