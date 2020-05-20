<?php 
require_once(__DIR__."/curl.php");
require_once(__DIR__."/utility.php");
class locationHandler {
	public $lat;
	public $long;
	private $latArray;
	private $longArray;
	private $curl;
	private $utility;
	private $config;
	
	function __construct () {
		$this->utility = new utility(); 
		$this->config = $this->utility->getConfig();
		$this->curl = new curl($this->config->locationBaseUrl);
	}
	
	/**
	 *@desc Set lat and long to globals   
	 *@param string $city - name of city you want geo location from
	 *@return void
	*/
	public function setLatLong ($city){
		$data = $this->getGeoData($city);
 
		if(!empty($data)){
			foreach($data as $poshits){		//loops thorugh geodata result to get lat/longs
				$this->longArray[] = $poshits->visueltcenter[0];
				$this->latArray[] = $poshits->visueltcenter[1];	
			}
			$this->long = $this->getAveragePos($this->longArray);
			$this->lat = $this->getAveragePos($this->latArray);
		}
	}
	
	/**
	 *@desc Get geo data from curl call   
	 *@param string $city - name of city you want geo location from
	 *@return object - location data for city
	*/
	private function getGeoData($city){
		$city = $this->utility->urlEncodeString($city);
		
        $this->curl->setMethod("GET");
        $this->curl->setUri("/postnumre?q={$city}");
        $result = $this->curl->sendRequest(true);
		$this->curl->closeCurl();
		$result = json_decode($result);
		return $result;
	}

	/**
	 *@desc Get avg geo location from location array   
	 *@param array $posArray - array with 1 or more float values
	 *@return float
	*/
	private function getAveragePos($posArray){
		$posArray = array_filter($posArray); 		//removes empty values 
		$average = array_sum($posArray)/count($posArray);	//get the sum of array and divide with total number
		return $average;
	}
}

?>