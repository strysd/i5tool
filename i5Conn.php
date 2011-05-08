<?php
class i5Conn{

	/**
	 * resource from i5_connect
	 * @var Connection
	 */
	protected static $conn;

	/**
	 * options for i5_connect
	 * @var array
	 */
	private static $options = array(
		//TODO define Your options for i5_connect
	);

	/**
	 * basic parameters
	 * @var object
	 */
	private static $basic;

	/**
	 * if persistent connection
	 * @var boolean
	 */
	protected static $persistent;

	/**
	 * Open connection
	 * @throws Exception
	 */
	function __construct() {
		if(!isset(self::$conn) || !is_resource(self::$conn)){
			if(!isset(self::$basic) || !is_object(self::$basic)){
				require_once 'Zend/Config.php';
				self::$basic = new Zend_Config_Ini('setting.ini', 'conn');
				if(!isset(self::$basic->host) ||
				   !isset(self::$basic->user) ||
				   !isset(self::$basic->pass) ){
					throw new Exception('Basic parameters for i5_connect are undefined');
				}
			}
			self::$persistent = self::$basic->persistent;
			if(self::persistent){
				$ret = i5_pconnect(self::$basic->host, self::$basic->user,
								   self::$basic->pass, self::$options);
			} else {
				$ret =  i5_connect(self::$basic->host, self::$basic->user,
								   self::$basic->pass, self::$options);
			}
			if(!$ret){
				throw new Exception(i5_errormsg(), i5_errno());
			}
			self::$conn = $ret;
		}
	}

	/**
	 * Close non-persistent connection
	 * @see pclose() if You need to close persistent connection
	 * @throws Exception
	 */
	function __destruct() {
		if(self::$persistent){
			return;
		}
		if(isset(self::$conn) && is_resource(self::$conn)){
			$ret = i5_close(self::$conn);
			if(!$ret){
				throw new Exception(i5_errormsg(), i5_errno());
			}
			self::$conn = null;
		}
	}

	/**
	 * Close persistent connection
	 * @throws Exception
	 */
	function pclose() {
		if(!self::$persistent){
			throw new Exception('Can not use this function for non-persistent connection');
		}
		if(isset(self::$conn) && is_resource(self::$conn)){
			$ret = i5_pclose(self::$conn);
			if(!$ret){
				throw new Exception(i5_errormsg(), i5_errno());
			}
		}
	}

	/**
	 * Check if new connection
	 * @throws Exception
	 * @return string/int
	 * @see i5_get_property()
	 */
	function is_newConnection() {
		if(!self::$persistent){
			throw new Exception('Can not use this function for non-persistent connection');
		}
		return i5_get_property(I5_NEW_CONNECTION, self::$conn);
	}
	/**
	 * Converts input parameter
	 * @param array $in input parameters
	 * @param array $keys parameters to convert
	 * @see i5Pgm
	 * @see i5Command
	 */
	protected function inConvert(&$in, $keys = array()){
		//TODO Example for Japanese
		foreach ($keys as $key) {
			$in[$key] = mb_convert_encoding($in[$key], 'sjis-win', 'UTF-8');
		}
	}

	/**
	 * Converts output parameter
	 * @param array $out output parameters
	 * @param array $keys parameters to convert
	 * @see i5Pgm
	 * @see i5Command
	 */
	protected function outConvert(&$out, $keys = array()){
		//TODO Example for Japanese
		foreach ($keys as $key) {
			$out[$key] = mb_convert_encoding($out[$key], 'UTF-8', 'sjis-win');
		}
	}
}