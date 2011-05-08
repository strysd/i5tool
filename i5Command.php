<?php
require_once './i5Conn.php';
class i5Command extends i5Conn{

	function __construct() {
		if(!isset(parent::$conn)){
			//TODO confirm we can use
			parent::__construct();
		}
	}

	function __destruct() {
		//TODO confirm we can use
		parent::__destruct();
	}

	/**
	 * Run command
	 * @param string $command command name
	 * @param array $in input parameters
	 * @param array $out output parameters
	 * @param array $inConvert input parameters　to convert
	 * @param array $outConvert output parameters　to convert
	 * @throws Exception
	 * @return array
	 */
	function run($command, $in = array(), $out = array(), $inConvert = array(), $outConvert = array()) {
		$this->inConvert($in, $inConvert);
		if($in !== array() || $out !== array()) {
			$ret = i5_command($command, $in, $out, parent::$conn);
		} else {
			$ret = i5_remotecmd($command, parent::$conn);
		}
		if(!$ret){
			throw new Exception(i5_errormsg(), i5_errno());
		}
		$retValue = array();
		foreach ($out as $outKey => $outValue) {
			$retValue[$outValue] = $$outValue;
		}
		$this->outConvert($out, $outConvert);
		return $retValue;
	}

	/**
	 * Retreive system value
	 * @param string $name
	 * @throws Exception
	 * @return string
	 */
	function sysval($name) {
		$ret = i5_get_system_value($name, parent::$conn);
		if(!$ret){
			throw new Exception(i5_errormsg(), i5_errno());
		}
		return $ret;
	}
}