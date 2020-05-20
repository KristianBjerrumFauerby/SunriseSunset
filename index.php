<?php
include 'inputForm.html';
require_once(__DIR__."/sunCycleHandler.php");
require_once(__DIR__."/locationHandler.php");
require_once(__DIR__."/utility.php");

class sunCycle {
	private $selectedCity = "";
	private $selectedDate = "";
	private $dayToProcess = "";
	private $utility;
	private $location;
	private $config;
	function __construct () {
		$this->utility = new utility();
		$this->config = $this->utility->setTimezone();    //making sure timezone is correct 
	}

	/**
	 *@desc Main function of this class, squid of worktool
	 *@param string $city - must be a zipcity
	 *@param string $date - if empty it defaults to current day 	   	
	 *@return void - but echos data to html
	*/
	public function dataHandler($city,$date){
		$this->location = new locationHandler();
		$inputRes = $this->setInput($city,$date); 
		$this->location->setLatLong($this->selectedCity); 
		if(!$inputRes){ 					//checking if the input passed the validation
			echo "<b>Definitely not a City or a date from the Gregorian Calendar?</b>";
			exit;
		}
		$result = $this->getData();
		if(!$result){						//checking if there is location data pressent
			echo "<b>Sure thats a City (zipcity)?</b>";
			exit;
		}
		$cityToShow = $this->utility->uppercaseFirst($this->selectedCity); 
		echo "<h1>{$cityToShow}</h1>";
		foreach($result as $resLines){	
			echo "<b>{$resLines->day}</b><br>";
			echo "Sunrise: {$resLines->sunrise}<br>";
			echo "Sunset: {$resLines->sunset}<br>";
			echo "<br>";
		}
		exit;
	}
 
	/**
	 *@desc Handles user input and checks validations
	 *@param string $city - must be a zipcity
	 *@param string $date - if empty it defaults to current day 	   	
	 *@return boolean
	*/	
	private function setInput($city,$date){
		if(!$this->utility->validateCity($city)){
			return false;
		}else{
			if(!$this->utility->validateDate($date) && $date != ""){
				$date = $this->utility->convertDate($date);     //trying to convert data string if validation fails criteria
				if(!$date){
					return false;
				}
			}
			$this->selectedCity = $city; 
			if($date == ""){
				$this->selectedDate = $this->utility->getCurrentDay();
			}else{
				$this->selectedDate = $date;
			}	
			return true;
		}
	}
	
	/**
	 *@desc Handles sunCycle based on city and day to be processed	   	
	 *@return array - final data set with all processed days
	*/	
	private function getData(){
		$result = array();
		if(empty($this->location->lat)){
			return false;
		}
		$days = $this->getDaysToShow();
		foreach($days as $day){
			$this->dayToProcess = $day;
			$result[] = $this->getSunCycle();
		}
		return $result;
	}
	
	/**
	 *@desc Handles sunCycle based on city and day to be processed	   	
	 *@return object - (day,sunrise,sunset) 
	*/	
	private function getSunCycle(){
		$sunCycleHandler = new sunCycleHandler();
		$result = $sunCycleHandler->getSunCycleInfo($this->location->lat,$this->location->long,$this->dayToProcess);
		$retval = (object)array();
		$retval->day = $this->dayToProcess;
		$retval->sunrise = $this->utility->getFormattedTime($result->results->sunrise);
		$retval->sunset = $this->utility->getFormattedTime($result->results->sunset);		
		return $retval;
	}

	/**
	 *@desc Generates a array with dates based on user selected start date untill sunday in same week	   	
	 *@return array - days to process
	*/
	private function getDaysToShow(){
		$datesToShow = array();
		$timestamp = $this->utility->getTimestamp($this->selectedDate);
		$dayOfweek = $this->utility->getDayOfWeek($timestamp);
		
		for($dayOfweek; $dayOfweek <= 7; $dayOfweek++){ 		//creating days array from start day until sunday where sunday is the 7th day of a week
			$datesToShow[] = $this->utility->getFormattedDate($timestamp);	//places date string in array 
			$timestamp = $this->utility->getNextDay($timestamp);	//creates timestamp for next day
		}
		return $datesToShow;
	}
	


}
/**
 *@desc start processing when user hits the button	   	
*/

if(isset($_POST['city'])){
	$city = $_POST['city'];
	$date = $_POST['date'];
	$sunCycle = new sunCycle();
	$sunCycle->dataHandler($city,$date);	
}



?>