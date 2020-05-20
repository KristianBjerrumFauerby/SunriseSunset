<?php
require_once(__DIR__."/curl.php");

class sunCycleHandler {
	private $utility;
	private $config;
	
	function __construct () {
		$this->utility = new utility(); 
		$this->config = $this->utility->getConfig();
		$this->curl = new curl($this->config->sunCycleBaseUrl);
	}
	
	/**
	 *@desc Get sunCycle date from curl   
	 *@param float $lat - lat coordinate
	 *@param float $long - long coordinate
	 *@param string $date - formatted yyyy-mm-dd date string
	 *@return object - sunCycle data for date 
	*/	
	public function getSunCycleInfo($lat,$long,$date=""){
		$this->curl->setMethod("GET");
		if($date != ""){
			$this->curl->setUri("/json?lat={$lat}&lng={$long}&date={$date}&formatted=0");
		}else{
			$this->curl->setUri("/json?lat={$lat}&lng={$long}&formatted=0");	
		}
        
        $result = $this->curl->sendRequest(true);
		$this->curl->closeCurl();
		$data = json_decode($result);
		return $data;
	}
}
?>