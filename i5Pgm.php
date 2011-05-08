<?php
require_once './i5Conn.php';
class i5Pgm extends i5Conn{

	/**
	 * Program id array
	 * @var array
	 */
	protected static $pgmid = array();

	/**
	 * Open program resource
	 * @param string/int $id Program id as You like. For example, 'summerizeTable'
	 * @param string $pgm real name of libray and program. For example, 'YOURLIB/YOURPGM'
	 * @param array $description defines input/output parameters
	 * @throws Exception
	 */
	function __construct($id, $pgm, $description = array()) {
		if(!isset(self::$pgmid($id)) || !self::$pgmid($id)){
			if(!isset(parent::$conn)){
				//TODO confirm we can use
				parent::__construct();
			}
			$ret = i5_program_prepare($pgm, $description, parent::$conn);
			if(!$ret){
				throw new Exception(i5_errormsg(), i5_errno());
			}
			self::$pgmid($id) = $ret;
		}
	}

	function __destruct() {
		foreach (self::$pgmid as $id) {
			$this->close($id);
		}
		//TODO confirm we can use
		parent::__destruct();
	}

	/**
	 * Close program resource
	 * @param string/int $id Program id as You like. For example, 'summerizeTable'
	 * @throws Exception
	 */
	function close($id) {
		if(isset(self::$pgmid($id)) && is_resource(self::$pgmid($id))){
			$ret = i5_program_close(self::$pgmid($id));
			if(!$ret){
				throw new Exception(i5_errormsg(), i5_errno());
			}
		}
	}

	/**
	 * Call program
	 * @param string/int $id Program id as You like. For example, 'summerizeTable'
	 * @param array $in input parameters
	 * @param array $out output parameters
	 * @param array $inConvert input parameters　to convert
	 * @param array $outConvert output parameters　to convert
	 * @throws Exception
	 * @return array
	 */
	function call($id, $in = array(), $out = array(), $inConvert = array(), $outConvert = array()) {
		if(!isset(self::$pgmid($id)) || !self::$pgmid($id)){
			throw new Exception('This program id is not defined');
		}
		$this->inConvert($in, $inConvert);
		$ret = i5_program_call(self::$pgmid($id), $in, $out);
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
}