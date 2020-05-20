<?php

class utility {
	
	function __construct () {

	}
	
	/**
	 *@desc Get config file 
	 *@return object
	*/
	public function getConfig (){
		return json_decode(file_get_contents('config.json'));	
	}

	/**
	 *@desc Validates user city input - may not contain interger and special chars 
	 *@param string $city - must be a zipcity	   	
	 *@return boolean
	*/
	public function validateCity ($city){
		if(!preg_match('/[0-9-!$%^&*()_+|~=`{}\[\]:";\'<>?,.\/]/',$city) && $city != ""){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 *@desc Validates user date input - must be in propper format yyyy-mm-dd
	 *@param string $date - yyyy-mm-dd	   	
	 *@return boolean
	*/
	public function validateDate ($date){
		if(preg_match('/[1-2]{1}[0-9]{1}[0-9]{1}[0-9]{1}[-][0-1]{1}[0-9]{1}[-][0-3]{1}[0-9]{1}/',$date)){
			return true;
		}else{
			return false;
		}
	}
	
	/**
	 *@desc Generates current day in format yyyy-mm-dd
	 *@return string - yyyy-mm-dd
	*/
	public function getCurrentDay (){
		return date("Y-m-d");
	}
	
	/**
	 *@desc Converts a datestring to format yyyy-mm-dd
	 *@param string $date - yyyy-mm-dd	
	 *@return string - yyyy-mm-dd 
	 *@return boolean - if failing converting  
	*/
	public function convertDate ($date){
		$date = strtotime($date); 
		if(!$date){ 						//making sure that the date string is infact a date string
			return false;
		}
		return date("Y-m-d",$date);
	}
	
	/**
	 *@desc Getting epoch timestamp from date string and add 2 hours
	 *@param string $date - yyyy-mm-dd	
	 *@return integer - epoch timestamp 
	*/
	public function getTimestamp ($date){
		$timestamp = strtotime($date);
		$timestamp = $timestamp + 7200; 	//making sure timestamp is between 00:00:00 - 23:59:59 and not on the edge 
		return $timestamp;
	}
	
	/**
	 *@desc Generate integer representative value of the day from a timestamp
	 *@param integer $timestamp - epoch timestamp
	 *@return integer - representative value of the day 
	*/
	public function getDayOfWeek ($timestamp){
		return date("N",$timestamp);
	}
	
	/**
	 *@desc Generates formatted time string hours:minuts:seconds from timestamp 
	 *@param integer $timestamp - epoch timestamp
	 *@return string - hours:minuts:seconds 
	*/
	public function getFormattedTime($timestamp){
		return date("H:i:s",strtotime($timestamp));
	}
	
	/**
	 *@desc Generates formatted date string yyyy-mm-dd from timestamp 
	 *@param integer $timestamp - epoch timestamp
	 *@return string - yyyy-mm-dd
	*/
	public function getFormattedDate($timestamp){
		return date("Y-m-d",$timestamp);
	}
	
	/**
	 *@desc Generates generate timestamp for next day based on epoch timestamp
	 *@param integer $timestamp - epoch timestamp
	 *@return integer - epoch timestamp
	*/
	public function getNextDay($timestamp){
		return $timestamp + 86400;
	}
	
	/**
	 *@desc Uppercase first letter in a string
	 *@param string $string - Any normal char string
	 *@return string - first letter uppercased string
	*/
	public function uppercaseFirst($string){
		return ucfirst($string);
	}
	
	/**
	 *@desc urlencode a string
	 *@param string $url - part of url to encode (city name)
	 *@return string - url encoded string part
	*/
	public function urlEncodeString($url){
		return urlencode($url);				//making sure that user input with øæå is handled
	}
	
	/**
	 *@desc Set Timezone for application and restore it after execution
	 *@return void
	*/
	public function setTimezone(){
		ini_set('date.timezone',$this->getConfig()->timeZone);		//temporary change timezone while executing
	}
}

?>