<?php
require_once './i5Conn.php';
class i5Command extends i5Conn{

    function __construct() {
        if(!isset(self::$conn)){
            try {
                parent::__construct();
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }
    }

    function __destruct() {
        parent::__destruct();
    }

    /**
     * Run command
     * @param string $command command name
     * @param array $in input parameters
     * @param array $out output parameters
     * @throws Exception
     * @return array
     */
    function run($command, $in = array(), $out = array()) {
        if($in !== array() || $out !== array()) {
            $ret = i5_command($command, $in, $out, self::$conn);
        } else {
            $ret = i5_remotecmd($command, self::$conn);
        }
        if(!$ret){
            throw new Exception(i5_errormsg(), i5_errno());
        }
        $retValue = array();
        foreach ($out as $outKey => $outValue) {
            $retValue[$outValue] = $$outValue;
        }
        return $retValue;
    }

    /**
     * Retreive system value
     * @param string $name
     * @throws Exception
     * @return string
     */
    function sysval($name) {
        $ret = i5_get_system_value($name, self::$conn);
        if(!$ret){
            throw new Exception(i5_errormsg(), i5_errno());
        }
        return $ret;
    }
}